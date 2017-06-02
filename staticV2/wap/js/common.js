function toChnDigit(num) {
    var t = parseInt(num);
    if (t == 0) return "零";
    if (t == 1) return "一";
    if (t == 2) return "二";
    if (t == 3) return "三";
    if (t == 4) return "四";
    if (t == 5) return "五";
    if (t == 6) return "六";
    if (t == 7) return "七";
    if (t == 8) return "八";
    if (t == 9) return "九";
    return "";
}
//@CheckItem@ OPT-HuTie-20031208 优化：添加屏蔽所有按钮的公用函数 
function disableAllButtons() {
    for (var i = 0; i < document.all.tags("input").length; i++) {
        var tmp = document.all.tags("input")[i];
        if (tmp.type == "button" || tmp.type == "submit" || tmp.type == "reset") {
            tmp.disabled = true;
        }
    }
}


//函数名：checkNUM 
//功能介绍：检查是否为数字 
//参数说明：要检查的数字 
//返回值：1为是数字，0为不是数字 
function checkNum(Num) {
    var i, j, strTemp;
    strTemp = "0123456789.";
    if (Num.length == 0) return 0
    for (i = 0; i < Num.length; i++) {
        j = strTemp.indexOf(Num.charAt(i));
        if (j == -1) {
            //说明有字符不是数字 
            return 0;
        }
    }
    //说明是数字 
    return 1;
}
//函数名：checkNUM 
//功能介绍：检查是否为数字 
//参数说明：要检查的数字 
//返回值：1为是数字，0为不是数字 
function checkIntNum(Num) {
    var i, j, strTemp;
    strTemp = "0123456789";
    if (Num.length == 0) return 0
    for (i = 0; i < Num.length; i++) {
        j = strTemp.indexOf(Num.charAt(i));
        if (j == -1) {
            //说明有字符不是数字 
            return 0;
        }
    }
    //说明是数字 
    return 1;
}
//函数名：checkEmail 
//功能介绍：检查是否为Email Address 
//参数说明：要检查的字符串 
//返回值：0：不是 1：是 
function checkEmail(a) {
    //@CheckItem@ Bug141-hutie-20030821 修改界面:Email地址要做禁止中文校验 
    var reg = /[^\u0000-\u00FF]/;
    if (a.match(reg) != null) {
        return 0; //有汉字 
    }
    var i = a.length;
    var temp = a.indexOf('@');
    var tempd = a.indexOf('.');
    if (temp > 1) {
        if ((i - temp) > 3) {
            if ((i - tempd) > 0) {
                return 1;
            }
        }
    }
    return 0;
}
//函数名：checkTEL 
//功能介绍：检查是否为电话号码 
//参数说明：要检查的字符串 
//返回值：1为是合法，0为不合法 
function checkTel(tel) {
    var i, j, strTemp;
    strTemp = "0123456789- ()";
    for (i = 0; i < tel.length; i++) {
        j = strTemp.indexOf(tel.charAt(i));
        if (j == -1) {
            //说明有字符不合法 
            return 0;
        }
    }
    //说明合法 
    return 1;
}
//函数名：checkLength 
//功能介绍：检查字符串的长度 
//参数说明：要检查的字符串 
//返回值：长度值 
function checkLength(strTemp) {
    var i, sum;
    sum = 0;
    for (i = 0; i < strTemp.length; i++) {
        //@CheckItem@ BUG-Renhj-20040604 优化：将验证的函数改成128以类的为单字符。避免“·”符号 
        // if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255)) 
        if ((strTemp.charCodeAt(i) >= 0) && (strTemp.charCodeAt(i) <= 128)) sum = sum + 1;
        else sum = sum + 2;
    }
    return sum;
}
//函数名：checkSafe 
//功能介绍：检查是否含有"'", '"',"<", ">", ";", "、" 
//参数说明：要检查的字符串 
//返回值：0：是 1：不是 
function checkSafe(a) {
    fibdn = new Array("'", '"', ">", "<", "、", ";");
    i = fibdn.length;
    j = a.length;
    for (ii = 0; ii < i; ii++) {
        for (jj = 0; jj < j; jj++) {
            temp1 = a.charAt(jj);
            temp2 = fibdn[ii];
            if (temp1 == temp2) {
                return 0;
            }
        }
    }
    return 1;
}
//函数名：checkChar 
//功能介绍：检查是否含有非字母字符 
//参数说明：要检查的字符串 
//返回值：0：含有 1：全部为字母 
function checkChar(str) {
    var strSource = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.()& ";
    var ch;
    var i;
    var temp;
    for (i = 0; i <= (str.length - 1); i++) {
        ch = str.charAt(i);
        temp = strSource.indexOf(ch);
        if (temp == -1) {
            return 0;
        }
    }
    return 1;
}
//函数名：checkCharOrDigital 
//功能介绍：检查是否含有非数字或字母 
//参数说明：要检查的字符串 
//返回值：0：含有 1：全部为数字或字母 
function checkCharOrDigital(str) {
    var strSource = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ.()& ";
    var ch;
    var i;
    var temp;
    for (i = 0; i <= (str.length - 1); i++) {
        ch = str.charAt(i);
        temp = strSource.indexOf(ch);
        if (temp == -1) {
            return 0;
        }
    }
    return 1;
}
//函数名：checkChinese 
//功能介绍：检查是否含有汉字 
//参数说明：要检查的字符串 
//返回值：0：含有 1：没有 
function checkChinese(strTemp) {
    var i, sum;
    for (i = 0; i < strTemp.length; i++) {
        if ((strTemp.charCodeAt(i) < 0) || (strTemp.charCodeAt(i) > 255)) return 0;
    }
    return 1;
}
//函数名：compareTime() 
//功能介绍： 比较时间大小 
//参数说明：beginYear开始年，beginMonth开始月,benginDay开始日,beginH开始小时，beginM开始分钟， 
// endYear结束年，endMonth结束月，endMonth结束日,endH结束小时，endM结束分钟 
//返回值：true 表示 开始时间大于结束时间，false 相反 
function compareTime(beginYear, beginMonth, benginDay, beginH, beginM, endYear, endMonth, endDay, endH, endM) {
    var date1 = new Date(beginYear, beginMonth - 1, benginDay, beginH, beginM);
    var date2 = new Date(endYear, endMonth - 1, endDay, endH, endM);
    if (date1.getTime() > date2.getTime()) {
        return false;
    }
    return true;
}
//函数名：compareDate() 
//功能介绍： 比较日期大小 
//参数说明：beginYear开始年，beginMonth开始月,benginDay开始日 
// endYear结束年，endMonth结束月，endMonth结束日 
//返回值：0：true 表示 开始时间大于结束时间，false 相反 
function compareDate(beginYear, beginMonth, benginDay, endYear, endMonth, endDay) {
    var date1 = new Date(beginYear, beginMonth - 1, benginDay);
    var date2 = new Date(endYear, endMonth - 1, endDay);
    if (date1.getTime() > date2.getTime()) {
        return false;
    }
    return true;
}
//函数名：checkUrl 
//功能介绍：检查Url是否合法 
//参数说明：要检查的字符串 
//返回值：true：合法 false：不合法。 
function checkURL(strTemp) {
    if (strTemp.length == 0) return false;
    if (checkChinese(strTemp) == 0) return false;
    if (strTemp.toUpperCase().indexOf("HTTP://") != 0 && strTemp.toUpperCase().indexOf("HTTPS://") != 0) {
        return false;
    }
    return true;
}
// @CheckItem@ OPT-Renhj-20030704 提供公共的去处空格的方法 
//清除左边空格 
function js_ltrim(deststr) {
    if (deststr == null) return "";
    var pos = 0;
    var retStr = new String(deststr);
    if (retStr.lenght == 0) return retStr;
    while (retStr.substring(pos, pos + 1) == " ") pos++;
    retStr = retStr.substring(pos);
    return (retStr);
}
//清除右边空格 
function js_rtrim(deststr) {
    if (deststr == null) return "";
    var retStr = new String(deststr);
    var pos = retStr.length;
    if (pos == 0) return retStr;
    while (pos && retStr.substring(pos - 1, pos) == " ") pos--;
    retStr = retStr.substring(0, pos);
    return (retStr);
}
//清除左边和右边空格 
function js_trim(deststr) {
    if (deststr == null) return "";
    var retStr = new String(deststr);
    var pos = retStr.length;
    if (pos == 0) return retStr;
    retStr = js_ltrim(retStr);
    retStr = js_rtrim(retStr);
    return retStr;
}
//格式化输入的日期串，输入的如："2003-9-12" 输出："2003-09-12" 
function formatDateStr(inDate) {
    if (inDate == null || inDate == "") return "";
    var beginDate = inDate.split("-");
    var mYear = beginDate[0];
    var mMonth = beginDate[1];
    var mDay = beginDate[2];
    mMonth = ((mMonth.length == 1) ? ("0" + mMonth) : mMonth);
    mDay = ((mDay.length == 1) ? ("0" + mDay) : mDay);
    return mYear + "-" + mMonth + "-" + mDay;
}
//Added by wanghui 20031020 检查URL地址的端口的合法性，必须为小于65535的数字 
function checkPort(inValue1, inValue2) {
    //先检查第一个参数的合法性，如果第二个参数是null，则第一个参数表示短信业务的‘业务处理地址' 
    if (inValue1 != null && inValue1.value != "") {
        var array1 = inValue1.value.split(":");
        if (array1.length >= 4) { //如果以':'分割数组长度为四，则有两个':'，所以不合法。 
            if (inValue2 == null) alert("业务处理地址端口格式错误");
            else alert("业务访问地址端口格式错误");
            inValue1.focus();
            return false;
        }
        if (array1.length >= 3) {
            port1 = array1[2];
            if ((pos1 = port1.indexOf("/")) >= 0) { //查看端口后面有没有'/'，如果有，取该符号以前的就是端口 
                port1 = port1.substring(0, pos1)
            }
            if (port1 == "" || checkIntNum(port1) == 0 || port1 > 65535) {
                if (inValue2 == null) alert("业务处理地址端口必须为数字且不能大于65535");
                else alert("业务访问地址端口必须为数字且不能大于65535");
                inValue1.focus();
                return false;
            }
        }
    }
    //再检查第二个参数的合法性 
    if (inValue2 != null && inValue2.value != "") {
        var array2 = inValue2.value.split(":");
        if (array2.length >= 4) {
            alert("业务处理地址端口格式错误");
            inValue2.focus();
            return false;
        }
        if (array2.length >= 3) {
            port2 = array2[2];
            if ((pos2 = port2.indexOf("/")) >= 0) {
                port2 = port2.substring(0, pos2)
            }
            if (port2 == "" || checkIntNum(port2) == 0 || port2 > 65535) {
                alert("业务处理地址端口必须为数字且不能大于65535");
                inValue2.focus();
                return false;
            }
        }
    }
    return true;
}
//obj:数据对象 
//dispStr :失败提示内容显示字符串 
function checkUrlValid(obj, dispStr) {
    if (obj == null) {
        alert("传入对象为空");
        return false;
    }
    var str = obj.value;
    var urlpatern0 = /^https?:\/\/.+＄/i;
    if (!urlpatern0.test(str)) {
        alert(dispStr + "不合法：必须以'http:\/\/'或'https:\/\/'开头!");
        obj.focus();
        return false;
    }
    var urlpatern2 = /^https?:\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?.+＄/i;
    if (!urlpatern2.test(str)) {
        alert(dispStr + "端口号必须为数字且应在1－65535之间!");
        obj.focus();
        return false;
    }
    var urlpatern1 = /^https?:\/\/(([a-zA-Z0-9_-])+(\.)?)*(:\d+)?(\/((\.)?(\?)?=?&?[a-zA-Z0-9_-](\?)?)*)*＄/i;
    if (!urlpatern1.test(str)) {
        alert(dispStr + "不合法,请检查!");
        obj.focus();
        return false;
    }
    var s = "0";
    var t = 0;
    var re = new RegExp(":\\d+", "ig");
    while ((arr = re.exec(str)) != null) {
        s = str.substring(RegExp.index + 1, RegExp.lastIndex);
        if (s.substring(0, 1) == "0") {
            alert(dispStr + "端口号不能以0开头!");
            obj.focus();
            return false;
        }
        t = parseInt(s);
        if (t < 1 || t > 65535) {
            alert(dispStr + "端口号必须为数字且应在1－65535之间!");
            obj.focus();
            return false;
        }
    }
    return true;
}
//函数名：checkVisibleEnglishChr 
//功能介绍：检查是否为可显示英文字符( !"#＄%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~) 
//参数说明：要检查的字符串 
//返回值：true|false 
//add by renhj 2004.01.05 
function checkVisibleEnglishChr(strTemp) {
    var i;
    for (i = 0; i < strTemp.length; i++) {
        if ((strTemp.charCodeAt(i) < 32) || (strTemp.charCodeAt(i) > 126)) return false;
    }
    return true;
}
//函数名：checkInputChr 
//功能介绍：检查是否含有非Input字符 
//参数说明：要检查的字符串 
//返回值：false：含有 true：全部为可Input字符 
//add by renhj 2004.01.05 
function checkInputChr(str) {
    var notinput = "\"'<>";
    var i;
    for (i = 0; notinput != null && i < notinput.length; i++) {
        if (str.indexOf(notinput.charAt(i)) >= 0) { //若有 
            return false;
        }
    }
    return true;
}
//函数名：checkTelExt 
//功能介绍：检查是否为电话号码，并且无分机号 
//参数说明：要检查的字符串 
//返回值：1为是合法，0为不合法 
function checkTelExt(tel) {
    var i, count, isNumber;
    count = 0; //有几个连续的数字串 
    isNumber = 0; //不是数字 
    for (i = 0; i < tel.length; i++) {
        //判断当前是否数字 
        if (checkIntNum(tel.charAt(i)) == 1) {
            if (isNumber == 0) {
                count = count + 1;
            }
            isNumber = 1;
        } else {
            isNumber = 0;
        }
        if (count > 2) {
            //说明有字符不合法或者有分机号 
            return 0;
        }
    }
    if ((checkIntNum(tel.charAt(0)) == 1) && (checkIntNum(tel.charAt(tel.length - 1)) == 1)) {
        //说明合法 
        return 1;
    } else {
        //说明有字符不合法 
        return 0;
    }
}
//函数名：checkFormdata 
//功能介绍：检查Form对象 
//参数说明： 
//obj：要检查的对象， 
//name：要检查的对象的中文名称， 
//length：检查的对象的长度（<0不检查）， 
//notnull:为true则检查非空， 
//notSpecChar:为true则检查有无特殊字符， 
//notChinessChar:为true则检查有无中文字符， 
//numOrLetter:为true则检查只能为数字或英文字母， 
//pNumber:为true则检查只能为正整数， 
//返回值：false：检查不通过 true：全部为可Input字符 
//add by renhj 2004.03.19 
//@CheckItem@ BUG:1641:718-Renhj-20040902-Add5 修改校验数字的信息 
function checkFormdata(obj, name, length, notnull, notSpecChar, notChinessChar, numOrLetter, pNumber) {
    //检查对象 
    if (!obj) {
        alert("目标不是对象，处理失败!");
        return false;
    }
    var msg;
    var ilen;
    //检测汉字 
    if (notChinessChar && (checkChinese(obj.value) != 1)) {
        msg = name + "不能包含汉字！";
        alert(msg);
        obj.focus();
        return false;
    }
    //检测特殊字符 
    if (notSpecChar && (!checkInputChr(obj.value))) {
        var notinput = "\"'<>";
        msg = name + "有非法字符（" + notinput + "）！";
        alert(msg);
        obj.focus();
        return false;
    }
    //检测长度 
    if (length >= 0 && (checkLength(obj.value) > length)) {
        ilen = length / 2;
        if (pNumber) {
            msg = name + "不能超过" + length + "个数字！";
        } else if (notChinessChar) {
            msg = name + "不能超过" + length + "个英文！";
        } else {
            msg = name + "不能超过" + length + "个英文或" + ilen + "个汉字！";
        }
        alert(msg);
        obj.focus();
        return false;
    }
    //检测非空 
    if (notnull && obj.value == "") {
        msg = "请输入" + name + "！";
        alert(msg);
        obj.focus();
        return false;
    }
    //检测只能为数字或英文字母 
    re = /[\W_]/;
    if (numOrLetter && re.exec(obj.value)) {
        msg = name + "只能为数字或英文字母！";
        alert(msg);
        obj.focus();
        return false;
    }
    //检测只能为只能为正整数 
    re = /[\D_]/;
    if (pNumber && re.exec(obj.value)) {
        msg = name + "只能为正整数！";
        alert(msg);
        obj.focus();
        return false;
    }
    return true;
}