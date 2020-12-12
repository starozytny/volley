function compareUsername(a, b){
    let objA = a.username;
    let objB = b.username;

    return comparison(objA, objB);
}

function comparison (objA, objB){
    let comparison = 0;
    if (objA > objB) {
        comparison = 1;
    } else if (objA < objB) {
        comparison = -1;
    }
    return comparison;
}

module.exports = {
    compareUsername
}