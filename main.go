package main

import (
	"fmt"
	"os"
	"time"

	"github.com/rs/zerolog"
	"github.com/rs/zerolog/log"
)

func main() {
	zerolog.TimestampFieldName = "timestamp"
	zerolog.SetGlobalLevel(zerolog.DebugLevel)
	file, err := os.OpenFile("./logs/logs-lambda-golang.log", os.O_APPEND|os.O_RDWR|os.O_CREATE, 0644)
	if err != nil {
		log.Panic().Err(err)
	}
	defer file.Close()

	logger := zerolog.New(file).With().Str("service", "lambda-golang-app").Timestamp().Logger()

	for range time.Tick(time.Second * 1) {
		logger.Info().Msg(fmt.Sprintf("%s %s", "Sending logs at", time.Now().String()))
	}

}
