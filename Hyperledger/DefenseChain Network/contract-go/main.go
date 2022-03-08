package main

import (
        "fmt"
        // "reflect"
        "github.com/hyperledger/fabric-contract-api-go/contractapi"
        "github.com/hhmidb/ClaimChainDev/ClaimChain/contract-go/insurance-claim"
)

func main() {

        contract := new(insuranceclaim.Contract)
        contract.TransactionContextHandler = new(insuranceclaim.TransactionContext)
        contract.Name = "org.claimchain.insuranceclaim"
        contract.Info.Version = "0.0.1"
        chaincode, err := contractapi.NewChaincode(contract)

        if err != nil {
                panic(fmt.Sprintf("Error creating chaincode. %s", err.Error()))
        }

        chaincode.Info.Title = "InsuranceClaimChaincode"
        chaincode.Info.Version = "0.0.1"

        err = chaincode.Start()

        if err != nil {
                panic(fmt.Sprintf("Error starting chaincode. %s", err.Error()))
        }
}
