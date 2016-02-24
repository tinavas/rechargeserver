1. Session management
2. Remember me option
3. Idle timeout
4. Dynamic template
5. Routing
6. htaccess protection
7. Default Admin panel
8. Account/IP lock for wrong attempt
9. Remove index.php from the url
10. 





**Features**

**Users :** Four level of user will be support of this site.
       * Level I
       * Level II
       * Level III
       * Level IV
       
Higher level of user(parent) will be able to create lower level user(child):

        * Admin will create Level I
        * Level I will create Level II
        * Level II will create Level III
        * Level III will create Level IV
User can see his child user list including current available of that child.


**User Creation :** User will be able to create his child by giving child email, password, service list (Mandatory).
While creating a new user parent will be able to assign any number of services. Parent will be able to update child informations. Any new service may be added or existing service may be disabled by the parent.

User(Parent) will able to forward balance to his child(Balance validation must be check after balance transfer).he will able to return back balance from his child if needed. Parent able to set service rate to his child and  also will be able to change serive rate any time of his child.


**Home Page History:** Shows all services short views that are provided by that site. 
User able to show total bkash uses of current day
and
Latest x number of payments,
Latest y number of payments.
**Service :**
the service that are provide by this site are
* bkash cashin
* dbbl cashin
* mcash cashin
* ucash cashin
* gp topup
* robi topup
* bl topup
* airtel topup
* teletalk topup 
Note:Since local server is not ready so only bkash cashin will be supported

**bkash :**
 bkash able to cashin by giving bkash number and amount and also show the last 10 transctions .

**Service History :** 
All transction will be displayed by user.

User will able to choose a specfic service transctions.


**Database Management :**

*Create reseller:* Parent allows to create reseller to his immediate child. We use four level of users

1. level1 allows to create child as level2.
2. level2 allows to create child as level3.
3. level3 allows to create child as level4.

while create a reseller affects the following tables:

     1. Users(users)
     2. User services(users_services).
     
Reseller's services assign by parent. When update parent services then also update child's services. reseler's rate also updated by prent and also update child's rate while update parent rate (if child is gaterthen parent).
the tables affect for these: 

    1.user services(user_services).

*Payments:* balance payments are 

  1. Load balance(for admin)
  2. Balance payment from parent to child
  3. Balance return from child to parent by parent
  4. Balance return to parent by child
  that time affect the table is 
     1.user Payment (user_payments)

*Use services:* Reseller allows to use the following services

    1.Top up
    2.Bkash
    3.DBBL
    4.M-Cash
    5.U-cash
    6.Global TopUp
  
that time affects the folloing tables

     1. User Transacions(user_transction)
     2. User Payments(user_payments)
     3. User Profit (user_profits)


