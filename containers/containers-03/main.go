package main

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"

	"github.com/gorilla/mux"
)

func handler(w http.ResponseWriter, r *http.Request) {
	body, err := ioutil.ReadAll(r.Body)
	if err != nil {
		http.Error(w, "Unable to read request body", http.StatusBadRequest)
		return
	}

	// Define a slice to hold the parsed integers
	var numbers []int

	// Parse the JSON body
	err = json.Unmarshal(body, &numbers)
	if err != nil {
		http.Error(w, "Invalid request body. Expected an array of integers.", http.StatusBadRequest)
		return
	}

	// Return the sum.
	sum := 0
	for _, num := range numbers {
		sum += num
	}

	fmt.Fprintf(w, "%d", sum)
}

func main() {
	router := mux.NewRouter()
	router.HandleFunc("/", handler)
	fmt.Println("Server starting on port 8080...")
	http.ListenAndServe(":8080", router)
}
