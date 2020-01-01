# Instruction on setup

```bash
git clone https://github.com/iam-raihan/key-value-store-laravel.git
cd key-value-store-laravel
./serve

```

then visit http://localhost:8000/api/values

# List of Technologies Used
* Laravel
* MySQL
* Redis
* Docker

# List of Language / Framework Features Used
Name                             | Reason
-------------------------------- | --------------------------------
Model, Eloquent                  | ORM
Migration, Seeder, Factory       | Seeding 100k rows within few seconds
Event, Event Subscriber          | // Not all code deserves to be in <br> // controller and if else conditions
Synchronous Job <br> Delayed Job | Update TTL, cache in redis, <br> Delete expired records in MySQL
Request                          | Custom Request class to preprocess inputs
Telescope                        | To monitor queries, requests, jobs, events
Unit Tests (php unit)            | // No project deadline
Trait (php)                      | Unified Json response helper class

# Requirements Check List
### Must be done
- ✅ Use appropriate status codes with all the responses
- ✅ Values can be of arbitrary length
- ✅ Remove all values stored over more than 5 minutes. Set a TTL.
- ✅ Has to be FAST

### Good to have
- ✅ Reset TTL on every GET Request
- ✅ Must be fault-tolerant, persistent
- ✅ It’s a plus if you can run the service with single command
- ✅ Writing test case is also encourged if possible

# Some facts
- With such requirements (api based), I would prefer to use Lumen instead. Laravel has many services/middlewares enabled by default that are not required. For ex. Session, Auth ... These stuffs makes our life easy but slowes down the load time of the application. Well of course these services can be removed (from app.php and kernel.php) depending on other functionalities.

- Eloquent is great but when it comes to read operations in large table, it slowes down for some obvious reasons. I n this application, I don't need the added benefits of Eloquent. So I used DB Facade instead (for read operations). I also did a quick benchmark among Eloquent > DB > PDO. [see here](#quick-benchmark-on-eloquent-db-pdo-read-operation)

- Using redis in this application but I am not that brave to rely completely on redis as a database although it has some persistance capabilities. In real projects, TTL can be of an hour or a day. So I used MySql as a backup. All write operations to MySql can be done in Queued jobs or Scheduled task.

- I could write a 'Scheduled Event' in MySql to delete expired records. But that's completely DB coupled solution, not the framework based and so very bad approach. Although that has some benefits as well. However I ended up with the delayed job thing.  [see here](#my-sql-scheduled-event)

# More Facts (Areas of improvement)
- Using two application, one for handling requests and sending back as soon as possible, another one one for the jobs like update cache ttl, database expiresAt time, delete expired records from mysql. And communicate in between by redis pub/sub or other message broker tools. These actually depends on real scenario.

- MySQL isn't the best fit as a Database in this scenario.

- Instead of creating 1 delayed job per request to delete records from DB, A Laravel task scheduler can be implemented. That may run every minute to delete expired records. Well, that also introduces upto ttl+1 minute delay. If that's acceptable, then this is a better approach.

- Pagination can implemented.

- And of course still plenty of room for more improvements.

# TODO
- Handle Exceptions
- More Test Cases

# Quick Benchmark on Eloquent, DB, PDO read operation
- // Code <br>
![benchmarking code](https://i.ibb.co/q0tRc2s/benchmarking.png)
- // Result <br>
![benchmarking result](https://i.ibb.co/2MgY4sy/benchmark-result.png)

# My SQL Scheduled Event
- Just showing an alternative. Not used in the project. Bad Approach.
![Scheduled Event](https://i.ibb.co/dPV0q8L/Scheduled-Event.png)
