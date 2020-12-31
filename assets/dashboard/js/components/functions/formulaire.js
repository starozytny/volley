const toastr = require("toastr");

function displayErrors(error, self, message="Veuillez vérifier les informations transmises."){
    if(Array.isArray(error.response.data)){
        self.setState({ errors: error.response.data });
    }else{
        if(error.response.data.message){
            toastr.error(error.response.data.message)
        }else{
            toastr.error(message);
        }
    }
}

function loader(status){
    let loader = document.querySelector('#loader');
    if(status){
        loader.style.display = "flex";
    }else{
        loader.style.display = "none";
    }
}

module.exports = {
    loader,
    displayErrors
}