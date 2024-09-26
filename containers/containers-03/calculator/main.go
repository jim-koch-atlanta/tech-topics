package main

import (
	"fmt"
	"io"
	"net/http"
	"strconv"
	"strings"

	"github.com/gorilla/mux"
)

// Helper function to parse a possible addition equation string
func parseAdditionEquation(equation string) ([]int, error) {
	// Remove all spaces to normalize the input
	equation = strings.ReplaceAll(equation, " ", "")

	// Split the equation using '+' as the delimiter, while handling negative numbers
	parts := strings.Split(equation, "+")

	var numbers []int
	for _, part := range parts {
		// Convert the part to an integer
		num, err := strconv.Atoi(part)
		if err != nil {
			return nil, fmt.Errorf("invalid number in equation: %s", part)
		}
		numbers = append(numbers, num)
	}
	return numbers, nil
}

func handler(w http.ResponseWriter, r *http.Request) {
	body, err := io.ReadAll(r.Body)
	if err != nil {
		http.Error(w, "Unable to read request body", http.StatusBadRequest)
		return
	}

	strBody := string(body)
	fmt.Fprintf(w, "Received request body: %s", strBody)

	// Define a slice to hold the parsed integers
	numbers, err := parseAdditionEquation(strBody)
	if err != nil {
		http.Error(w, "Unable to parse request body", http.StatusBadRequest)
		return
	}

	fmt.Fprintf(w, "Parsed numbers: %s", fmt.Sprint(numbers))
}

func main() {
	router := mux.NewRouter()
	router.HandleFunc("/", handler)
	fmt.Println("Server starting on port 8080...")
	http.ListenAndServe(":8080", router)
}
