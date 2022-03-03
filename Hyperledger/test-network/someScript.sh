#!/bin/bash
for ((id = 1051; id <= 2005; id++))
do
	peer chaincode invoke -o localhost:7050 --ordererTLSHostnameOverride orderer.example.com --tls --cafile /home/jack/git_target/ClaimChainDev/test-network/organizations/ordererOrganizations/example.com/orderers/orderer.example.com/msp/tlscacerts/tlsca.example.com-cert.pem -C mychannel -n claimchain --peerAddresses localhost:7051 --tlsRootCertFiles /home/jack/git_target/ClaimChainDev/test-network/organizations/peerOrganizations/org1.example.com/peers/peer0.org1.example.com/tls/ca.crt --peerAddresses localhost:9051 --tlsRootCertFiles /home/jack/git_target/ClaimChainDev/test-network/organizations/peerOrganizations/org2.example.com/peers/peer0.org2.example.com/tls/ca.crt --peerAddresses localhost:11051 --tlsRootCertFiles /home/jack/git_target/ClaimChainDev/test-network/organizations/peerOrganizations/org3.example.com/peers/peer0.org3.example.com/tls/ca.crt -c '{"function":"Issue","Args":["Shelter", "7000","'$id'"]}'

done
