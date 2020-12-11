function loader(status){
    let loader = document.querySelector('#loader');
    if(status){
        loader.style.display = "flex";
    }else{
        loader.style.display = "none";
    }
}

module.exports = {
    loader
}