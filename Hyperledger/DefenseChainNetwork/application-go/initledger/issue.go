package initledger

import (
	"fmt"
	"path/filepath"
	"log"

	"github.com/hhmidb/ClaimChainDev/ClaimChain/contract-go/insurance-claim"
	"github.com/hyperledger/fabric-sdk-go/pkg/gateway"
)

func Issue(wallet *gateway.Wallet) error {
	ccpPath := filepath.Join(
                "..",
                "..",
                "test-network",
                "organizations",
                "peerOrganizations",
                "org1.example.com",
                "connection-org1.yaml",
        )

	gw, err := gateway.Connect(
                gateway.WithConfig(config.FromFile(filepath.Clean(ccpPath))),
                gateway.WithIdentity(wallet, "Aimee"),
        )
	if err != nil {
                log.Fatalf("Failed to connect to gateway: %v", err)
        }
        defer gw.Close()

	network, err := gw.GetNetwork("mychannel")
        if err != nil {
                log.Fatalf("Failed to get network: %v", err)
        }

	contract := network.GetContract("claimchain")

	log.Println("--> Submit Transaction: Issue, creates new asset with issuer and damage value")
        result, err = contract.SubmitTransaction("Issue", "Shelter", 1000)
        if err != nil {
                log.Fatalf("Failed to Submit transaction: %v", err)
        }
        log.Println(string(result))
}
