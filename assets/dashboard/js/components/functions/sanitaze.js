function sanitizeString(chaine){
    chaine.trim();

    let spe = [' ','é','ê','è','à','ô','ï','ä', 'ö', 'ë', '<','>', '\''];
    let changer = ['-','e','e','e','a','o','i','a', 'o', 'e', '-','-', ''];

    spe.forEach((elem, index) => {
        chaine = chaine.replace(elem, changer[index]);
    })
    chaine = chaine.replace(/\s+/g, '-');
    chaine = chaine.toLowerCase();

    return chaine;
}

module.exports = {
    sanitizeString
}