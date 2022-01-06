Hello there!
It's pretty simple to configure my app in your environment, just do the following:

1- import the attached knowledge_city_db.sql to your database, db name should be 'knowledge_city_db'.
2- both username and password should be root.
3- there are three main APIs (login, logout and getStudent) :
    login: POST method.
    logout: POST method.
    getStudent: GET method.
4- I did very simple routing for the APIs.
5- there's a postman collection attached also, but keep in mind that after you call logout, you can not get any info (I added a test token in postman), so you have to 
    log in again and take the cookie value and put it as a Bearer Authorization value in the request header.
6- I was asked to do just the backend code, but I thought it would be better to be a full app, so I did the front end also with jQuery - Ajax - css.
7- the front-end is not matching the photos in the task, but I did it just so that we could fully experience the app through a user interface.
8- username: tito232 , password: test.

If there's anything I forgot to say, please feel free to text me on Telegram @tito232.

I tried to keep it simple.

Regards,
Tarek