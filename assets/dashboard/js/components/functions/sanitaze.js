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

function toFormatTime(date, timezone="UTC"){
    let ne = date.toLocaleString('fr-FR', { timeZone: timezone })
    return ne.substr(ne.length - 8,ne.length);
}

function toFormatDate(date, timezone="UTC"){
    return date.toLocaleDateString('fr-FR', { timeZone: timezone });
}

function toFormatDateTime(date, timezone="UTC"){
    return date.toLocaleString('fr-FR', { timeZone: timezone });
}

module.exports = {
    sanitizeString,
    toFormatTime,
    toFormatDate,
    toFormatDateTime
}