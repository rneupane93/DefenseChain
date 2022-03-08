package initledger

import (
	"fmt"
	"path/filepath"
	"io/ioutil"

	"github.com/hyperledger/fabric-sdk-go/pkg/gateway"
)

func PopulateWallet(wallet *gateway.Wallet) error {
	credPath := filepath.Join(
                "..",
                "..",
                "test-network",
                "organizations",
                "peerOrganizations",
                "org1.example.com",
                "users",
                "User1@org1.example.com",
                "msp",
        )

	certPath := filepath.Join(credPath, "signcerts", "User1@org1.example.com-cert.pem")
	// read the certificate pem
        certificate, err := ioutil.ReadFile(filepath.Clean(certPath))
        if err != nil {
                return err
        }

	keyPath := filepath.Join(credPath, "keystore", "priv_sk")
        // read the private key file
	privateKey, err := ioutil.ReadFile(filepath.Clean(keyPath))
        if err != nil {
                return err
        }

	identity := gateway.NewX509Identity("Org1MSP", string(certificate), string(privateKey))

	return wallet.Put("Aimee", identity)
}
