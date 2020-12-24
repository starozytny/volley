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

function toFormatTime(date){
    let ne = date.toLocaleString('fr-FR', { timeZone: 'UTC' })
    return ne.substr(ne.length - 8,ne.length);
}

function toFormatDate(date){
    return date.toLocaleDateString('fr-FR', { timeZone: 'UTC' });
}

function toFormatDateTime(date){
    return date.toLocaleString('fr-FR', { timeZone: 'UTC' });
}

module.exports = {
    sanitizeString,
    toFormatTime,
    toFormatDate,
    toFormatDateTime
}