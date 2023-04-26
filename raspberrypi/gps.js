const gpsd = require('gpsd');
const mysql = require('mysql2');

const dbConfig = {
    host: 'localhost',
    user: 'user',
    password: 'password',
    database: 'database_name',
};

const pool = mysql.createPool(dbConfig);

const daemon = new gpsd.Daemon({
    program: 'gpsd',
    device: '/dev/ttyUSB0',
    port: 2947,
    pid: '/tmp/gpsd.pid',
    logger: {
        info: console.log,
        warn: console.warn,
        error: console.error,
    },
});

daemon.start(() => {
    const listener = new gpsd.Listener();
    listener.connect(() => {
        listener.watch();
    });

    listener.on('TPV', (data) => {
        const query = `INSERT INTO gps_data (latitude, longitude) VALUES (${data.lat}, ${data.lon})`;
        pool.query(query, (err, result) => {
            if (err) {
                console.error(err);
            } else {
                console.log('GPS data stored successfully');
            }
        });
    });
});
