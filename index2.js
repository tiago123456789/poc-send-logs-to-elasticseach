const winston = require('winston');

const logger = winston.createLogger({
    level: 'info',
    format: winston.format.combine(
        winston.format.timestamp(),
        winston.format.json()
    ),
    defaultMeta: { service: 'lambda-node-2' },
    transports: [
        new winston.transports.File({
            dirname: "./logs",
            filename: 'logs-lambda-node-2.log'
        }),
    ],
});



setInterval(() => {
    logger.info(`Test winston setup ${new Date()}`)
}, 1 * 1000)

