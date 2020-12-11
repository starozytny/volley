function update(context, data, element){
    let newData = [];

    switch (context){
        default:
            newData = data;
            newData.push(element)
            break;
    }

    return newData
}

module.exports = {
    update
}