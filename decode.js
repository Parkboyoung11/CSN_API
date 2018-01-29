function decode_download_url(firstPart, middlePart, lastPart, lock) {
    var charArray = ['U', 'W', 'J', 'H', 'D', 'G', 'M', 'A', 'Y', 'I', 'X', 'N', 'R', 'L', 'B', 'P', 'K'];
    var numberArray = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'c', 'u', 'f', 'r', '1', '1', '2'];
    if (lock > 0) {
        for (var count = 0; count < charArray['length']; count++) {
            re = new RegExp(charArray[count], 'g');                      count=0 => re = /U/g
            middlePart = middlePart['replace'](re, numberArray[count])
        }
    };
    return (firstPart + middlePart + lastPart)
}