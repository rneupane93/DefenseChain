package main

import (
  "fmt"
  "html/template"
  "log"
  "net/http"
  "time"

)

type PageVariables struct {
	Date         string
	Time         string
}

func main() {
	http.HandleFunc("/", ApprovePage)
    http.Handle("/css/", http.StripPrefix("/css/", http.FileServer(http.Dir("./css"))))
  http.HandleFunc("/issue", IssuePage)
  http.HandleFunc("/issued", UserIssued)
  http.HandleFunc("/approve", ApprovePage)
  http.HandleFunc("/approveclaim", ApproveClaimPage)

	log.Fatal(http.ListenAndServe(":8081", nil))
}


func UserIssued(w http.ResponseWriter, r *http.Request){
  r.ParseForm()
  // r.Form is now either
  // map[animalselect:[cats]] OR
  // map[animalselect:[dogs]]
 // so get the animal which has been selected
  DamageValue := r.Form.Get("DamageValue")
  // fmt.Fprintf(w,DamageValue)
  response := issue(DamageValue) 
  fmt.Fprintf(w,response)

 //  Title := "Your preferred animal"
 //  MyPageVariables := PageVariables{
 //    PageTitle: Title,
 //    Answer : youranimal,
 //    }

 // // generate page by passing page variables into template
 //    t, err := template.ParseFiles("select.html") //parse the html file homepage.html
 //    if err != nil { // if there is an error
 //      log.Print("template parsing error: ", err) // log it
 //    }

 //    err = t.Execute(w, MyPageVariables) //execute the template and pass it the HomePageVars struct to fill in the gaps
 //    if err != nil { // if there is an error
 //      log.Print("template executing error: ", err) //log it
 //    }
}

func IssuePage(w http.ResponseWriter, r *http.Request){
    now := time.Now() // find the time right now
    HomePageVars := PageVariables{ //store the date and time in a struct
      Date: now.Format("02-01-2006"),
      Time: now.Format("15:04:05"),
    }
    t, err := template.ParseFiles("issue.html") //parse the html file homepage.html
    if err != nil { // if there is an error
      log.Print("template parsing error: ", err) // log it
    }
    err = t.Execute(w, HomePageVars) //execute the template and pass it the HomePageVars struct to fill in the gaps
    if err != nil { // if there is an error
      log.Print("template executing error: ", err) //log it
    }
}


func ApproveClaimPage(w http.ResponseWriter, r *http.Request){
    now := time.Now() // find the time right now
    HomePageVars := PageVariables{ //store the date and time in a struct
      Date: now.Format("02-01-2006"),
      Time: now.Format("15:04:05"),
    }
    t, err := template.ParseFiles("approveClaim.html") //parse the html file homepage.html
    if err != nil { // if there is an error
      log.Print("template parsing error: ", err) // log it
    }
    err = t.Execute(w, HomePageVars) //execute the template and pass it the HomePageVars struct to fill in the gaps
    if err != nil { // if there is an error
      log.Print("template executing error: ", err) //log it
    }
}

func ApprovePage(w http.ResponseWriter, r *http.Request){
    now := time.Now() // find the time right now
    HomePageVars := PageVariables{ //store the date and time in a struct
      Date: now.Format("02-01-2006"),
      Time: now.Format("15:04:05"),
    }
    t, err := template.ParseFiles("approve.html") //parse the html file homepage.html
    if err != nil { // if there is an error
      log.Print("template parsing error: ", err) // log it
    }
    err = t.Execute(w, HomePageVars) //execute the template and pass it the HomePageVars struct to fill in the gaps
    if err != nil { // if there is an error
      log.Print("template executing error: ", err) //log it
    }
}

func HomePage(w http.ResponseWriter, r *http.Request){

    now := time.Now() // find the time right now
    HomePageVars := PageVariables{ //store the date and time in a struct
      Date: now.Format("02-01-2006"),
      Time: now.Format("15:04:05"),
    }

    t, err := template.ParseFiles("homepage.html") //parse the html file homepage.html
    if err != nil { // if there is an error
  	  log.Print("template parsing error: ", err) // log it
  	}
    err = t.Execute(w, HomePageVars) //execute the template and pass it the HomePageVars struct to fill in the gaps
    if err != nil { // if there is an error
  	  log.Print("template executing error: ", err) //log it
  	}
}