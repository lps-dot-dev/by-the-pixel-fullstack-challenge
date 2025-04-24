# Fullstack Challenge

## Goal
Using Laravel and Vue.js, create an application which shows the weather for a set of users to demonstrate your coding chops.

## Acceptance Criteria
Instructions are purposely left somewhat open-ended to allow the developer to make some of their own decisions on implementation and design. We have provided some initial scaffolding structure/examples, however feel free to make it your own and remove anything unnecessary.

1. Clone this repository to your own GitHub account (do not fork).
2. Create a branch off of the `main` branch to do your work on.
3. Chose your own weather API, such as:
   - https://openweathermap.org/api
   - https://www.weather.gov/documentation/services-web-api
4. Show a list of users and their current weather.
   - Use the twenty randomized users generated from the seeder process, each having their own unique location (longitude and latitude).
   - The current weather conditions shown here should be no older than 1 hour.
5. Clicking a user should open a modal or screen, which shows that user's detailed weather report.
   - The current weather conditions shown here should be no older than 1 hour.
6. Internal API request(s) to retrieve weather data should take no longer than 500ms.
   - Consider that external APIs could and will take longer than this from time to time and should be accounted for.
7. The availability of external APIs is not guaranteed and should not cause the page to crash.

Once completed:
1. Open a PR to merge the branch you did your work on into the `main` branch so our team can provide code review comments.
2. Send a link of your repository to the interviewer and let them know how long the exercise took.

## Things to consider
- Redis is available (Docker service) if you wish to use it.
- Queues, workers, websockets could be useful.
- Feel free to use a frontend UI library such as PrimeVue, Vuetify, Bootstrap, Tailwind, etc. 
- Include anything else you desire to show off your coding chops!

## What we are looking for
- Attention to detail
- Testability
- Best practices
- Design patterns
- This is not a designer test so the frontend does not have to look "good," but of course bonus points if you can make it look appealing.

## To run the local dev environment

### API
- Navigate to `/api` folder
- Ensure [Docker](http://docker.com/get-started/) installed is active on host
- Although `entrypoint.sh` can copy the `.env.example` file via `cp .env.example .env`. I still recommend you manually do this yourself as you will still need to provide your [OpenWeatherMap.org](https://openweathermap.org/current) API key in the `OPENWEATHER_API_KEY` variable.
- Start docker containers `docker compose up` (add `-d` to run detached)
- The entry point of the app container has been set to `./docker/entrypoint.sh` and will automate the following setup for you:
   1. It will copy `.env.example` to `.env` for you (if `.env` is not already present)
   2. It will install any dependencies via `composer install`
   3. It will generate an `APP_KEY` via `php artisan key:generate` (only if the `APP_KEY` variable is missing or empty)
   4. Run database migrations via `php artisan migrate` (health checks are run on the MySQL container prior to ensure it is up and running before the start of this container, so this should not present any race conditions)
   5. Laravel unit tests are executed via `php artisan test --testsuite=Unit` (at this point, `apache2` is not serving our application, therefore we cannot run any `Feature` tests that rely on dependencies)
   6. The following services are initiated and handled by `supervisord`:
      - `/usr/sbin/apache2ctl -D FOREGROUND`
      - `php /var/www/html/artisan queue:work redis --sleep=3 --tries=3`
      - `php /var/www/html/artisan websockets:serve`

- Connect to container to run commands: `docker exec -it fullstack-challenge-app-1 bash`
  - Make sure you are in the `/var/www/html` path
  - Install php dependencies: `composer install`
  - Setup app key: `php artisan key:generate`
  - Migrate database: `php artisan migrate` 
  - Seed database: `php artisan db:seed`
  - Run tests: `php artisan test`
- Visit api: [http://localhost](http://localhost)

### Frontend
- Navigate to `/frontend` folder
- Copy .env.example: `cp .env.example .env`
- Ensure nodejs v18 is active on host
- Install javascript dependencies: `npm install`
- Run frontend: `npm run dev`
- Visit frontend: [http://localhost:5173](http://localhost:5173)

## Thoughts/Considerations
As the number of `users` grows, we may not be able to hit the `500ms` target response time. It may be necessary to use the websocket approach (like weather updates) in the future.

Testing is not my forte, so I did not come up with any meaningful tests.

Using a public channel to broadcast weather isn't necessarily bad. However, if multiple users are using the app, and someone manually requests a weather update, all users will be pushed that update.

That's not intended, perhaps manual weather updates should push events and data in a private channel specific to the user requesting the update.

Right now, the update weather endpoint just dispatches an update weather event. I think it would be a good idea to maybe serve the cached version of the weather (if available).

However, I am unsure if this will scale well. Lets say, in the future, we are working with 1k or 10k weather records. Parsing them into JSON could perhaps take longer than `500ms`, so this may not be plausible.

Lastly, I am taking responses from the API as absolute truths. While I am handling response errors gracefully, I am expecting successful responses to have a certain shape/structure.

I am using null-safe operators to prevent the frontend from crashing but I should be validating the responses I get from the server to make sure I have all the information I need to properly display components.
