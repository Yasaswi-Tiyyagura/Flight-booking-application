const validation = new JustValidate("#register");

validation
    .addField("#phonenumber", [
        {
            rule: "required"
        },
        {
            rule: "phonenumber"
        },
        {
            validator: (value) => () => {
                return fetch("validate-phone.php?phonenumber=" + encodeURIComponent(value))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (json) {
                        return json.available;
                    });
            },
            errorMessage: "Phone Number already taken"
        }
    ])
    .addField("#firstname", [
        {
            rule: "required"
        }
    ])
    .addField("#lastname", [
        {
            rule: "required"
        }
    ])
    .addField("#age", [
        {
            rule: "required"
        }
    ])
    .addField("#email", [
        {
            rule: "required"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    .addField("#password_confirmation", [
        {
            validator: (value, fields) => {
                return value === fields["#password"].elem.value;
            },
            errorMessage: "Passwords should match"
        }
    ])
    .onSuccess((event) => {
        document.getElementById("register").submit();
    });