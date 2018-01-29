var encode = decode_download_url("abcd+", "WYMUUgK-IKdDRAXe","+ahhdhs", 1);
window.print(encode);
function decode_download_url(_0x3f16x2, _0x3f16x3, _0x3f16x4, _0x3f16x5) {
    var _0x3f16x6 = ['U', 'W', 'J', 'H', 'D', 'G', 'M', 'A', 'Y', 'I', 'X', 'N', 'R', 'L', 'B', 'P', 'K'];
    var _0x3f16x7 = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'c', 'u', 'f', 'r', '1', '1', '2'];
    if (_0x3f16x5 > 0) {
        for (var _0x3f16x8 = 0; _0x3f16x8 < _0x3f16x6['length']; _0x3f16x8++) {
            re = new RegExp(_0x3f16x6[_0x3f16x8], 'g');
            _0x3f16x3 = _0x3f16x3['replace'](re, _0x3f16x7[_0x3f16x8])
        }
    };
    return (_0x3f16x2 + _0x3f16x3 + _0x3f16x4)
}