This is where we will create the Smart Contracts and applications for ClaimChain. It is modeled after the structure in commercial-paper.

Contracts:
EnrollUser(licenseID)
This function creates a unique user id and enrolls a unique individual in their respective organization.
OpenPolicy(userID, vIN)
This function creates a policy with a user and a unique vehicle and assigns a unique policyID.
IssueClaim(userID, policyID, damage)
This function creates a new claim on a policy under and indivdual and attaches a damage value.
ApproveClaim(claimID, settlementValue)
This function approves an issued claim and assigns a settlement value.

Applications:

Web Application:

export PATH=${PWD}/../bin:$PATH
export FABRIC_CFG_PATH=$PWD/../config/

peer chaincode invoke -o localhost:7050 --ordererTLSHostnameOverride orderer.example.com --tls --cafile /home/george/Documents/ClaimChainDev/test-network/organizations/ordererOrganizations/example.com/orderers/orderer.example.com/msp/tlscacerts/tlsca.example.com-cert.pem -C mychannel -n claimchain --peerAddresses localhost:7051 --tlsRootCertFiles /home/george/Documents/ClaimChainDev/test-network/organizations/peerOrganizations/org1.example.com/peers/peer0.org1.example.com/tls/ca.crt --peerAddresses localhost:9051 --tlsRootCertFiles /home/george/Documents/ClaimChainDev/test-network/organizations/peerOrganizations/org2.example.com/peers/peer0.org2.example.com/tls/ca.crt --peerAddresses localhost:11051 --tlsRootCertFiles /home/george/Documents/ClaimChainDev/test-network/organizations/peerOrganizations/org3.example.com/peers/peer0.org3.example.com/tls/ca.crt -c '{"function":"Issue","Args":[Shelter, 1000]}'
