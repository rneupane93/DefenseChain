package insuranceclaim

import ledgerapi "github.com/hhmidb/ClaimChainDev/ClaimChain/contract-go/ledger-api"

// ListInterface defines functionality needed
// to interact with the world state on behalf
// of an insurance claim
type ListInterface interface {
        AddClaim(*InsuranceClaim) error
        GetClaim(string, string) (*InsuranceClaim, error)
        UpdateClaim(*InsuranceClaim) error
}

type list struct {
        stateList ledgerapi.StateListInterface
}

func (icl *list) AddClaim(claim *InsuranceClaim) error {
        return icl.stateList.AddState(claim)
}

func (icl *list) GetClaim(issuer string, claimNumber string) (*InsuranceClaim, error) {
        ic := new(InsuranceClaim)

        err := icl.stateList.GetState(CreateInsuranceClaimKey(issuer, claimNumber), ic)

        if err != nil {
                return nil, err
        }

        return ic, nil
}

func (icl *list) UpdateClaim(claim *InsuranceClaim) error {
        return icl.stateList.UpdateState(claim)
}

// NewList creates a new list from context
func newList(ctx TransactionContextInterface) *list {
        stateList := new(ledgerapi.StateList)
        stateList.Ctx = ctx
        stateList.Name = "org.claimchain.insuranceclaimlist"
        stateList.Deserialize = func(bytes []byte, state ledgerapi.StateInterface) error {
                return Deserialize(bytes, state.(*InsuranceClaim))
        }

        list := new(list)
        list.stateList = stateList

        return list
}
