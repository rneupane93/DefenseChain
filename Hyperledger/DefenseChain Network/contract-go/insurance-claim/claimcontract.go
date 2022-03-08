package insuranceclaim

import (
	"fmt"
	//"time"
	//"math/rand"

	"github.com/hyperledger/fabric-contract-api-go/contractapi"
	//"github.com/google/uuid"
)

// SimpleContract contract for handling writing and reading from the world state
type Contract struct {
	contractapi.Contract
}

func (c *Contract) Issue(ctx TransactionContextInterface, issuer string, damageValue int, claimID string) (*InsuranceClaim, error) {
	/*issueDateTime := time.Now().String()
	r := rand.New(rand.NewSource(1))
	uuid.SetRand(r)
	claimID, _ := uuid.NewRandomFromReader(r)*/
	claim := InsuranceClaim{ClaimID: claimID, Issuer: issuer /*, IssueDateTime: issueDateTime*/, DamageValue: damageValue}
	claim.SetIssued()

	err := ctx.GetClaimList().AddClaim(&claim)

	if err != nil {
		return nil, err
	}

	return &claim, nil
}

func (c *Contract) Approve(ctx TransactionContextInterface, issuer string, claimID string, settlementValue int) (*InsuranceClaim, error) {
	claim, err := ctx.GetClaimList().GetClaim(issuer, claimID)

	if err != nil {
		return nil, err
	}

	if claim.IsApproved() {
		return nil, fmt.Errorf("Claim %s:%s is already approved.", issuer, claimID)
	}

	claim.SetApproved()

	claim.SettlementValue = settlementValue

	err = ctx.GetClaimList().UpdateClaim(claim)

	if err != nil {
		return nil, err
	}

	return claim, nil
}
