function setErrors (error, values) {
    let errors = [];

    if (error.response) {
        error.response.data.map(err => {

            let val = "";
            for (const [key, value] of Object.entries(values)) {
                if(key === err.name){
                    val = value.value
                }
            }

            errors[err.name] = {
                value: val,
                error: err.message
            };

        })
    }

    return errors;
}

module.exports = {
    setErrors
}