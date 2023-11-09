```diff
- avoid adding this README file to pull request
```

# Capstone 2 Project

# Official Branch for UI Development

### UI Priority

1. Forms
2. Tables
3. Dashboard(Admin,Different UI per usertype)

### Forms

| Priority No |           Form            |                      File Name                      |
| :---------: | :-----------------------: | :-------------------------------------------------: |
|      1      | Login Form(Admin & Users) | <b>Admin</b>(admin/index.php) <b>User</b> login.php |
|      2      |       Register Form       |                    register.php                     |
|      3      |     OTP verification      |                verification_otp.php                 |
|      4      |       Edit Profile        |                  profile_edit.php                   |
|      5      |   Account Verification    |              verification_account.php               |

## Concessionaire Monitoring Operations System

- Clone this repo

```
git clone https://github.com/Capstone-COMS/coms.git
```

- Create Database named <b>coms</b>

### User Panel (Owner/Tenant)

- Open http://localhost/coms/register.php ,use your Email

### Admin Panel

- Open http://localhost/coms/admin/

<b>Admin Email

```
coms.system.adm@gmail.com
```

<b>Admin Password

```
password
```

# Take Note

- Always write code/ make changes and push in the development branch.  
  -- to switch to ui-development branch

```
git checkout ui-development
```

- Add -> Commit and push your code

```
git add .

git commit -m "WRITE CHANGES HERE"

git push -u origin ui-development
```
