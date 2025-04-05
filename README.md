### Changes done
1. Added feature tests for AdminController and ProductController
2. Refactored the controller method request with extended request classes with detailed validation
3. Added action architecture to the controller core logic
4. Added resource class to handle the collection of products
5. Added observer to observe the product price change
6. Converting exchange rate getter from CURL execution to HTTP and making it as Support class like laravel did
7. Added caching system for the exchange rate that will update in set time to reduce the API request and keep the performance
8. Added enum to handle currency
9. Refactored the console command
10. Added unit test to ensure the console command works properly
11. Refactored assets loading system from primitive laravel to vite
12. Added laravel pint to ensure the code clarity and cleanliness

### Improvement Suggestion
1. Implement storage based upload for the product and install spatie media-library
2. Implement filament or livewire to handle the front-end to add more interactivity of the app
3. Implement notification instance instead of mail so in the future when there is push notification or SMS, it will be easier to maintain
4. Implement PEST for more eloquent testing
5. Implement laravel translation to handle the string for better localisation in the future
6. Split the AdminController into a folder with AdminController and ProductController when the app gets bigger