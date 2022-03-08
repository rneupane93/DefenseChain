package insuranceclaim

import (
        "encoding/json"
        "fmt"

        ledgerapi "github.com/hhmidb/ClaimChainDev/ClaimChain/contract-go/ledger-api"
)

// State enum for insurance claim state property
type State uint

const (
        // ISSUED state for when a claim has been issued
        ISSUED State = iota + 1
        // APPROVED state for when a claim is approved
        APPROVED
)

func (state State) String() string {
        names := []string{"ISSUED", "APPROVED"}

        if state < ISSUED || state > APPROVED {
                return "UNKNOWN"
        }

        return names[state-1]
}

// CreateInsuranceClaimKey creates a key for insurance claims
func CreateInsuranceClaimKey(issuer string, claimID string) string {
        return ledgerapi.MakeKey(issuer, claimID)
}

// Used for managing the fact status is private but want it in world state
type insuranceClaimAlias InsuranceClaim
type jsonInsuranceClaim struct {
        *insuranceClaimAlias
        State State  `json:"currentState"`
        Class string `json:"class"`
        Key   string `json:"key"`
}

// InsuranceClaim defines an insurance claim
type InsuranceClaim struct {
        ClaimID		 string `json:"claimID"`
        Issuer           string `json:"issuer"`
        // IssueDateTime    string `json:"issueDateTime"`
        DamageValue      int    `json:"damageValue"`
	SettlementValue	 int	`json:"settlementValue"`
        state            State  `metadata:"currentState"`
        class            string `metadata:"class"`
        key              string `metadata:"key"`
}

// UnmarshalJSON special handler for managing JSON marshalling
func (ic *InsuranceClaim) UnmarshalJSON(data []byte) error {
        jic := jsonInsuranceClaim{insuranceClaimAlias: (*insuranceClaimAlias)(ic)}

        err := json.Unmarshal(data, &jic)

        if err != nil {
                return err
        }

        ic.state = jic.State

        return nil
}

// MarshalJSON special handler for managing JSON marshalling
func (ic InsuranceClaim) MarshalJSON() ([]byte, error) {
        jic := jsonInsuranceClaim{insuranceClaimAlias: (*insuranceClaimAlias)(&ic), State: ic.state, Class: "org.claimchain.insuranceclaim", Key: ledgerapi.MakeKey(ic.Issuer, ic.ClaimID)}

        return json.Marshal(&jic)
}

// GetState returns the state
func (ic *InsuranceClaim) GetState() State {
        return ic.state
}

// SetIssued returns the state to issued
func (ic *InsuranceClaim) SetIssued() {
        ic.state = ISSUED
}

// SetApproved sets the state to approved
func (ic *InsuranceClaim) SetApproved() {
        ic.state = APPROVED
}

// IsIssued returns true if state is issued
func (ic *InsuranceClaim) IsIssued() bool {
        return ic.state == ISSUED
}

// IsIssued returns true if state is issued
func (ic *InsuranceClaim) IsApproved() bool {
        return ic.state == APPROVED
}

// GetSplitKey returns values which should be used to form key
func (ic *InsuranceClaim) GetSplitKey() []string {
        return []string{ic.Issuer, ic.ClaimID}
}

// Serialize formats the insurance claim as JSON bytes
func (ic *InsuranceClaim) Serialize() ([]byte, error) {
        return json.Marshal(ic)
}

// Deserialize formats the insurance claim from JSON bytes
func Deserialize(bytes []byte, ic *InsuranceClaim) error {
        err := json.Unmarshal(bytes, ic)

        if err != nil {
                return fmt.Errorf("Error deserializing insurance claim. %s", err.Error())
        }

        return nil
}
