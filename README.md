# TEST
A Simple Deposit & Withdraw Application

## API
API build using express JS

1. `cd .\api\`
2. Change file .env.example to .env inside folder api
3. `npm install` to install node depedencies
4. Run migration `npx prisma migrate dev` will run migration and seed
5. `npm run dev` The app will run on http://127.0.0.1:3000

## EXTERNAL API
API build using express JS act as third party API. Always return 200

1. `cd .\externalapi\`
2. Change file .env.example to .env inside folder externalapi
3. `npm install` to install node depedencies
5. `npm run dev` The app will run on http://127.0.0.1:3001

## CLIENT
Client side build using Laravel 10, jQuery & Tailwind CSS

1. `cd .\client\`
2. Change file .env.example to .env
3. `composer install` to install composer depedencies
4. `npm install` to install node depedencies
5. `php artisan key:generate` to generate APP KEY for Laravel
6. `npm run dev` to start node. Need to run or style will not be applied
7. `php artisan serve` to start Laravel. The app will run on http://127.0.0.1:8000
8. > Try this email and password after seeding the database
   > email: firman.er@gmail.com
   > password: 123456

*if you found withdraw or deposit failed. It's because a random status set between success = 1 and failed = 2*

