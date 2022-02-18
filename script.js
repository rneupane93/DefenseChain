/* 

* Licensed under the Apache License, Version 2.0 (the "License"); 

* you may not use this file except in compliance with the License. 

* You may obtain a copy of the License at 

* 

* http://www.apache.org/licenses/LICENSE-2.0 

* 

* Unless required by applicable law or agreed to in writing, software 

* distributed under the License is distributed on an "AS IS" BASIS, 

* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. 

* See the License for the specific language governing permissions and 

* limitations under the License. 

*/ 

 
 

'use strict'; 

/** 

* @param {org.bcs.Mitigate} Mitigate 

* @transaction 

*/ 

 
 

async function mitigate(mx) { 

// Save the old value of the owner1 ip. 

// let oldValue = mx.task.task_owner.ip_address; 


 

// Update the asset with the new value of Ip specified in transaction. 

// ? 

//mx.pd.Ipaddress = mx.QVMIp; 

var length = mx.mitigation.mitigation_score.length 

var sum = 0 

mx.mitigation.mitigation_score.forEach(function(item,index) { 

sum = sum + item*(1/mx.mitigation.mitigation_response_time[index]) 

}); 

var final_score = sum * mx.mitigation.free_resources
  
if (final_score > 1) {
  final_score = 1
}
  
var reputation_var = 0;
// detect free riding by detector/mitigator
  console.log("00")
if (mx.QVMIp == "") {
  console.log("01")
  // free rider. Value/reputation at stake lost. Value given to requestor
  if (Number(mx.task.detectr_mitigatr_deposit.monetary_penalty) == 0) {
    // reputation add to requestor
    var r = Number(mx.requestor.reputation) + Number(mx.task.requestor_deposit.reputation_value);
    console.log("11")
    await updateParticipantReputation1(mx.requestor.orgId, r);
  } else {
    console.log("12")
    // money add to requestor
    var wallet = Number(mx.task.requestor_deposit.reputation_value) + Number(mx.requestor.wallet);
    updateParticipantWallet(mx.requestor.orgId, wallet)
  }
} else {
  console.log("13")
  // not free rider. return value/reputation put at stake
  if (Number(mx.task.detectr_mitigatr_deposit.monetary_penalty) == 0) {
    // reputation return
    console.log("14")
    reputation_var = reputation_var + Number(mx.task.detectr_mitigatr_deposit.reputation_value);
  } else {
    console.log("15")
    // money return
    var wallet = Number(mx.task.requestor_deposit.reputation_value) + Number(mx.mitigator.wallet);
    updateParticipantWallet(mx.mitigator.orgId, wallet)
  }
}
  console.log("16")
var reputation = calculateReputation(mx.reputation_list,  Number(mx.mitigator.reputation), final_score)
reputation = reputation_var + reputation;
await updateParticipant(mx, reputation, final_score)

// Emit an event for the modified asset. 

let event = getFactory().newEvent('org.bcs', 'MitigationEvent'); 

event.mitigation = mx.mitigation; 

event.QVMIp = mx.QVMIp; 

event.final_score = final_score.toString() 

emit(event); 

} 

 
 

/** 

* @param {org.bcs.Detect} Detect 

* @transaction 

*/ 
async function detect(mx) { 

// Save the old value of the owner1 ip. 

// let oldValue = mx.task.task_owner.ip_address; 

 
 

// Update the asset with the new value of Ip specified in transaction. 

// ? 

var sum = 0 

var suspiciousness_sum = 0 

mx.detection.v_i_vector_quant.forEach(function(item,index) { 

var sum = sum + item*(1/mx.detection.detection_response_time[index]) 

var suspiciousness_sum = suspiciousness_sum + mx.detection.suspiciousness_score[index] 

}); 

var final_score = mx.detection.resources *(( sum) + suspiciousness_sum) 

if (final_score > 1) {
  final_score = 1
}
  
  var reputation_var = 0;
// detect free riding by detector/mitigator
if (mx.QVMIp == "") {
  // free rider. Value/reputation at stake lost. Value given to requestor
  console.log("detect 1 ::")
  if (Number(mx.task.detectr_mitigatr_deposit.monetary_penalty) == 0) {
    console.log("detect 2 ::")
    // reputation added to requestor
    var r = Number(mx.requestor.reputation) + Number(mx.task.requestor_deposit.reputation_value);
    await updateParticipantReputation1(mx.requestor.orgId, r);
    console.log("detect 3 ::")
  } else {
    // money add to requestor
    console.log("detect 4 ::")
    var wallet = Number(mx.task.requestor_deposit.reputation_value) + Number(mx.requestor.wallet);
    updateParticipantWallet(mx.requestor.orgId, wallet)
  }
} else {
  console.log("detect 5 ::")
  // not free rider. return value/reputation put at stake
  if (Number(mx.task.detectr_mitigatr_deposit.monetary_penalty) == 0) {
    console.log("detect 6 ::")
    // reputation return
    reputation_var = reputation_var + Number(mx.task.detectr_mitigatr_deposit.reputation_value);
  } else {
    // money return
    console.log("detect 7 ::")
    var wallet = Number(mx.task.requestor_deposit.reputation_value) + Number(mx.mitigator.wallet);
    updateParticipantWallet(mx.mitigator.orgId, wallet)
  }
}
  console.log("detect 8 ::")
var reputation = calculateReputation(mx.reputation_list,  Number(mx.mitigator.reputation), final_score)
console.log(" DETECT ::: --- rep before add : ",reputation_var);
reputation = reputation_var + reputation;
  console.log("DETECT ::: --- rep after add : ",reputation);
  await updateParticipant(mx, reputation, final_score)
 console.log("detect 9 ::")
//commitPayment(mx.mitigator.reputation, reputation)
 console.log("detect 10 ::")
// Emit an event for the modified asset. 

let event = getFactory().newEvent('org.bcs', 'DetectionEvent'); 
 console.log("detect 11 ::")
event.detection = mx.detection; 
 console.log("detect 12 ::")
event.QVMIp = mx.QVMIp; 
 console.log("detect 13 ::")
event.final_score = final_score.toString() 
 console.log("detect 14 ::")
emit(event); 

}


/** 

* @param {org.bcs.SearchResolvers} SearchResolvers 

* @transaction 

*/ 
async function searchResolvers(sx) { 
console.log("sx.requestor : ",sx.requestor)
var factory = getFactory();
var organization_list = [];
organization_list = await getOrgAboveThreshold(sx.threshold_reputation,sx.requestor.orgId).then(a => {
	console.log("Asdsa : ",a);
  	a.forEach(function(organization) {
      console.log(organization)
      var taskObjArr=[]
      organization.task_list.forEach(function(task) {
        	var taskObj = {
        	task_id: task.task_id,
        	qom_qod: task.qom_qod,
        	free_rider: task.free_rider
     	 }
        	taskObjArr.push(taskObj);
     	 });
      
      var orgObj = {
          orgId:organization.orgId,
          organization_name:organization.organization_name,
          geographic_pin:organization.geographic_pin,
          type:organization.type,
          ip_address:organization.ip_address,
          reputation:organization.reputation,
          task_list:taskObjArr,
          wallet: organization.wallet
        }
      var org = factory.newResource('org.bcs', 'Organization', organization.orgId);
      org.orgId = organization.orgId;
      org.organization_name = organization.organization_name;
      org.geographic_pin = organization.geographic_pin;
      org.type = organization.type;
      org.ip_address = organization.ip_address;
      org.reputation = organization.reputation;
      org.task_list = organization.task_list;
      org.wallet = organization.wallet;

        organization_list.push(org);
        console.log(" :: organization:: ",organization);
    });
  console.log(" :: organization_list:: ",organization_list);
  return organization_list;
})
  console.log("searchResolvers ::")
  console.log(organization_list)

// Emit an event for the modified asset. 

let event = getFactory().newEvent('org.bcs', 'SearchResolverEvent'); 
console.log("sx.requestor : ",sx.requestor.orgId)
event.requestor_id = sx.requestor.orgId; 
console.log("organization_list : ",organization_list)
event.org_list = organization_list; 
  console.log(" sx.threshold_reputation :: ", sx.threshold_reputation)

event.threshold_reputation = sx.threshold_reputation; 

emit(event); 
}


/** 

* @param {org.bcs.SendRequest} SendRequest 

* @transaction 

*/ 
async function sendRequest(sx) { 
  // update Task status and deposit for requestor part
  
  sx.task.task_type = "REQUEST";
  sx.task.task_status = "TASK_CREATED";
  sx.task.requestor_deposit.type = "VALUE"
  // check if requestor adds value . if not do not allow to send request. since false reporting
  // to reduce free riding
 if (Number(sx.task.requestor_deposit.monetary_penalty) == 0 && 
     Number(sx.task.requestor_deposit.reward) == 0 ) {
    throw new Error('Please keep reward and (optional) monetary penalty for transaction to proceed')
  } else {
    // subtract penalty from wallet and hold in transaction
  //TODO : reduce deposit from wallet
    var subtract = Number(sx.requestor.wallet) - (Number(sx.task.requestor_deposit.monetary_penalty) +
                                                Number(sx.task.requestor_deposit.reward))
    if (subtract < 0) {
      throw new Error('Insufficient balance. Recharge wallet')
    } else {
      sx.requestor.wallet = String(subtract);
      console.log("subtract : sendREQ : ",subtract)
      updateParticipantWallet(sx.requestor.orgId,subtract)
    }
  }
  
  //create and add task to assets
  getAssetRegistry('org.bcs.Deposit')
  .then(function (depositAssetRegistry) {
    // Get the factory for creating new asset instances.
    var factory = getFactory();
    // Create the vehicle.
    var req_deposit = factory.newResource('org.bcs', 'Deposit', sx.task.requestor_deposit.deposit_id);
    var det_mit_deposit = factory.newResource('org.bcs', 'Deposit', sx.task.detectr_mitigatr_deposit.deposit_id);
    req_deposit.type = sx.task.requestor_deposit.type;
    req_deposit.reward = sx.task.requestor_deposit.reward;
    req_deposit.monetary_penalty = sx.task.requestor_deposit.monetary_penalty;
    req_deposit.total = sx.task.requestor_deposit.total;
    req_deposit.reputation_value = sx.task.requestor_deposit.reputation_value;
    req_deposit.owner = sx.task.requestor_deposit.owner;
    
    det_mit_deposit.type = sx.task.detectr_mitigatr_deposit.type;
    det_mit_deposit.reward = sx.task.detectr_mitigatr_deposit.reward;
    det_mit_deposit.monetary_penalty = sx.task.detectr_mitigatr_deposit.monetary_penalty;
    det_mit_deposit.total = sx.task.detectr_mitigatr_deposit.total;
    det_mit_deposit.reputation_value = sx.task.detectr_mitigatr_deposit.reputation_value;
    det_mit_deposit.owner = sx.task.detectr_mitigatr_deposit.owner;
    // Add the vehicle to the vehicle asset registry.
    return depositAssetRegistry.addAll([req_deposit,det_mit_deposit]);
  })
  .catch(function (error) {
    // Add optional error handling here.
  });
  
// Emit an event for the modified asset. 

let event = getFactory().newEvent('org.bcs', 'RequestEvent'); 
event.task = sx.task;
event.requestor = sx.requestor; 
event.mitigator = sx.mitigator; 
emit(event); 

}


/** 

* @param {org.bcs.AcceptRequest} AcceptRequest 

* @transaction 

*/ 
async function acceptRequest(sx) { 
  // update Task status and deposit for detector/mitigator part
  if (sx.mitigator.type == "DETECTOR") {
    sx.task.task_type = "DETECT";
  }
  else  if(sx.mitigator.type == "MITIGATOR") {
    sx.task.task_type = "MITGATE";
  }
  
  sx.task.task_status = "TASK_ACCEPTED";
  // check detector puts value or reputation at stake before accepting job. 
  // to reduce free riding
  
  //if (Number(sx.task.detectr_mitigatr_deposit.monetary_penalty) == 0 &&
  //   Number(sx.task.detectr_mitigatr_deposit.reputation_value) == 0 ) {
  if (Number(sx.stake_value) == 0) {
    throw new Error('Please keep some penalty value or own reputation at stake for transaction to proceed')
  } else {
    // subtract penalty from wallet and hold in transaction
  //TODO : reduce deposit from wallet
    var subtract;
    if (sx.type == "VALUE") {
    	subtract = Number(sx.mitigator.wallet) - Number((sx.stake_value))
      	if (subtract < 0) {
      		throw new Error('Insufficient balance. Recharge wallet.')
    	}
      	sx.mitigator.wallet = String(subtract);
     	updateParticipantWallet(sx.mitigator.orgId,subtract)
    } else {
      subtract = Number(sx.mitigator.reputation) - Number(sx.stake_value);
      if (subtract < 0) {
      		throw new Error('Insufficient reputation to put at stake.')
    	}
      sx.mitigator.reputation = String(subtract);
      updateParticipantReputation1(sx.mitigator.orgId,String(subtract))
    }
    updateTask(sx.task,sx.type,sx.stake_value)
    
  }

// Emit an event for the modified asset. 

let event = getFactory().newEvent('org.bcs', 'AcceptEvent'); 
event.task = sx.task;
event.requestor = sx.requestor; 
event.mitigator = sx.mitigator; 
emit(event); 

}




function updateParticipant(mx, reputation, final_score) {
  console.log("updateParticipant() ::: *************")
  var id = mx.mitigator.orgId
  var qvmip = mx.QVMIp
  var bool = false;
  if (qvmip == "") {
    bool = true
  }
  return getParticipantRegistry('org.bcs.Organization')
  .then(function (participantRegistry) {
    /// Get the specific participant from the participant registry.
    return participantRegistry.get(id);
  })
  .then(function (organization) {
    // Process the the org object.
    console.log("getOrganization1 :: " ,reputation);
    getParticipantRegistry('org.bcs.Organization')
      .then(function (participantRegistry) {
      // Modify the properties of the participant.
      organization.reputation = String(reputation);
      var factory = getFactory();
    var task = factory.newResource('org.bcs','TaskList',mx.task.task_id);
      organization.reputation = String(reputation);
      task.qom_qod = String(final_score)
      task.free_rider = bool
//      var task = {
  //      qom_qod: final_score,
    //    free_rider: bool
    //  }
      organization.task_list.push(task)
      console.log("After update organization = ",organization);
      return participantRegistry.update(organization);
    })
    .catch(function (error) {
    console.log("error1111 ::: " ,error)
    // Add optional error handling here.
  });
  })
  .catch(function (error) {
    console.log("error ::: " ,error)
    // Add optional error handling here.
  });
  
}


function updateParticipantReputation1(orgId, reputation) {
  return getParticipantRegistry('org.bcs.Organization')
  .then(function (participantRegistry) {
    /// Get the specific participant from the participant registry.
    return participantRegistry.get(orgId);
  })
  .then(function (organization) {
    // Process the the org object.
    console.log("getOrganization1 :: " ,organization.reputation);
    getParticipantRegistry('org.bcs.Organization')
      .then(function (participantRegistry) {
      // Modify the properties of the participant.
      organization.reputation = String(reputation);
      console.log("After update organization = ",organization);
      return participantRegistry.update(organization);
    })
    .catch(function (error) {
    console.log("error1 ::: " ,error)
    // Add optional error handling here.
  });
  })
  .catch(function (error) {
    console.log("error ::: " ,error)
    // Add optional error handling here.
  });
  
}

function updateParticipantWallet(orgId, wallet) {
  return getParticipantRegistry('org.bcs.Organization')
  .then(function (participantRegistry) {
    /// Get the specific participant from the participant registry.
    return participantRegistry.get(orgId);
  })
  .then(function (organization) {
    // Process the the org object.
    console.log("getOrganization1 :: " ,organization.wallet);
    getParticipantRegistry('org.bcs.Organization')
      .then(function (participantRegistry) {
      // Modify the properties of the participant.
      console.log(typeof(wallet));
      organization.wallet = String(wallet);
      console.log("After update organization = ",organization);
      return participantRegistry.update(organization);
    })
    .catch(function (error) {
    console.log("error1 ::: " ,error)
    // Add optional error handling here.
  });
  })
  .catch(function (error) {
    console.log("error ::: " ,error)
    // Add optional error handling here.
  });
  
}


function calculateReputation(reputation_list, old_reputation, score) {
  var max_rep = Math.max.apply(Math, reputation_list)
  var rep_avg = (reputation_list.reduce((previous, current) => current += previous))/reputation_list.length
  var reputation = Number(old_reputation)

  if (score > 0.5 &&  reputation > rep_avg) {
    reputation = Math.min(reputation + 1, max_rep)
    console.log(1)
  } 
  else if (score <= 0.5 && reputation >= rep_avg +1) {
    reputation = reputation -1
    console.log(2)
  }
  else if (score <= 0.5 && reputation == rep_avg) {
    reputation = 0
    console.log(3)
  }
  else if (reputation <= rep_avg +1) {
    reputation = reputation +1
    console.log(4)
  }
  console.log("reputation : ", reputation)
  return reputation
}

function commitPayment(old_reputation, new_reputation, task) {
  if (task.task_type == TASKTYPE.REQUEST) {
      
  } else {
    if (old_reputation < new_reputation) {
    	// payment fail?
  	 } else {
    	// payment success?
  	 }
  }
}

async function getOrgAboveThreshold(threshold_reputation,id) {
var organization_list = [];
  // Get the org participant registry.
return getParticipantRegistry('org.bcs.Organization')
  .then(function (participantRegistry) {
    // Get all of the orgs in the org participant registry.
    return participantRegistry.getAll();
  })
  .then(function (organizations) {
    console.log(organizations)
    // Process the array of driver objects.
    organizations.forEach(function (organization) {
      if(Number(organization.reputation) >= threshold_reputation
        && organization.type != "REQUESTOR" && organization.orgId != id) {
        //var orgObj = {
         // orgId:organization.orgId,
          //organization_name:organization.organization_name,
         // geographic_pin:organization.geographic_pin,
//          type:organization.type,
 //         ip_address:organization.ip_address,
  //        reputation:organization.reputation,
   //       task_list:organization.task_list
     //   }
        organization_list.push(organization);
        console.log(organization.orgId);
      }
    });
  return organization_list;
  })
  .catch(function (error) {
  console.log(error)
    // Add optional error handling here.
  });
}

function updateTask(task,type,value) {
  console.log("task value: ",value)
  return getAssetRegistry('org.bcs.Task')
  .then(function (taskRegistry) {
    task.detectr_mitigatr_deposit.type = type;
 	if (type == "VALUE") {
      console.log("updateTask : 1")
    	task.detectr_mitigatr_deposit.monetary_penalty = value;
  	} else {
            console.log("updateTask : 2")
    	task.detectr_mitigatr_deposit.reputation_value = value;
  	}
      return taskRegistry.update(task);
  })
  .catch(function (error) {
    console.log("error ::: " ,error)
    // Add optional error handling here.
  });

}


 