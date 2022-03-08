package insuranceclaim

import (
        "github.com/hyperledger/fabric-contract-api-go/contractapi"
)

// TransactionContextInterface an interface to
// describe the minimum required functions for
// a transaction context in the insurance
// claim
type TransactionContextInterface interface {
        contractapi.TransactionContextInterface
        GetClaimList() ListInterface
}

// TransactionContext implementation of
// TransactionContextInterface for use with
// insurance claim contract
type TransactionContext struct {
        contractapi.TransactionContext
        claimList *list
}

// GetClaimList returns claim list
func (tc *TransactionContext) GetClaimList() ListInterface {
        if tc.claimList == nil {
                tc.claimList = newList(tc)
        }

        return tc.claimList
}
