# What is Number2Text?
Number2Text is a php library that converts any digits-number into the equivalent text in 10 languages.

> For example $7431285.46 in English will be:seven million, four hundred and thirty-one thousand, two hundred and eighty-five dollars and forty-six cents

![enter image description here](http://egy1st.com/myhub/images/number2text.gif)

# Great For Banks Billing
Number2Text is useful when making financial reports, generating bills, and printing checks. There’s no need to type in each number – just pass your digits to Number2Text and a digits translation will appears next to your figures. Since encoding is automatic, there is no room for fraud or human error.

# Multiple Languages Support
Number2Text can converts numbers in the following languages:

1.  English
2.  Arabic
3.  French
4.  German
5.  Italian
6.  Spanish
7.  Portuguese
8.  Russian
9.  Turkish
10.  Persian

# All Languages Example
For Number $7431285.46

in English will be:

> seven million, four hundred and thirty-one thousand, two hundred and eighty-five dollars and forty-six cents

in French will be:

> sept millions quatre cent trente et un mille deux cent quatre-vingt-cinq euro et quarante-six cents

in German will be:

> sieben Millionen vierhunderteinunddreißigtausendzweihundertfünfundachtzig euro und sechsundvierzig cents

in Arabic will be:

> ![enter image description here](http://egy1st.com/myhub/images/num2text_arabic.jpg)

in Spanish will be:

> siete millones cuatrocientos treinta y uno mil doscientos ochenta y cinco euro cuarenta y seis cents

in Italian will be:

> sette milioni quattrocentotrentunomila duecentoottantacinque euro quarantasei cents

in Portuguese will be:

> sete milhões quatrocentos e trinta e um mil duzentos e oitenta e cinco euro quarenta e seis cents

in Russian will be:

> семь миллионов четыреста тридцать один тысяч двести восемьдесят пять рублей сорок шесть копеек

in Russian will be:

> семь миллионов четыреста тридцать один тысяч двести восемьдесят пять рублей сорок шесть копеек

in Turkish will be:

> yedi milyon dört yüz otuz bir bin iki yüz seksen beş lira kırk altı kurus

in Persian will be:

> ![enter image description here](http://egy1st.com/myhub/images/num2text_persian.png)

# Currency Options
Number2Text sets the currency depending on the language you choose. The default currency is dollar for the English converter, euro for French and German, and pound for Arabic. Standard fields allow you to set singular and plural units (dollar/dollars, cent/cents) and assign currency symbols ($) but still you can change them to your owns.

# Usage
1.  Download library and unzip it
2.  include file _Number2Text.php_ in your sourcecode
3.  call function _translateNumber_ and pass two parameters to it
    1.  digits-number
    2.  language-id , where langyage-id as listed in the follwong table



| Language | Arabic | English | French | German | Italian | Spanish | Portuguese | Russian | Turkish | Persian |
| -------- |------- |-------- |------- |------- |-------- |-------- |----------- |-------- |-------- |-------- |
| ID       | 1      | 2       | 3      | 4      | 5       | 6       | 7          | 8       | 9       | 10      |


# Example

    //include Number2Text.php file
    require_once "num2text/Number2Text.php" ; 

    //crate an instance of Number2Text
    $oTextNum = New Number2Text() ;

    // set currency names
    $oTextNum->setCurrency("dollar", "dollars", "cent", "cents")

    // send request
    $number = "785487.80" ;
    $language = 1;  // English
    $Num2Text = $oTextNum->translateNumber($number, $language) ;

    // print results
    echo $Num2Text ;

# Interactive Online demo
You may give Number2text a try from [here](http://demo.egy1st.com/num2text/)

# Watch Video Tutorial
You may watch a video tutorial from [here](https://vimeo.com/87768516)

# I believe in Freedom. It is Open Source
You may view all library source files here or at other repositories where it is published.

# Donation
500+ of development . Yes it is !! Donate to keep this library free while it is expected to be commercial. Thanks in advance.

[![Flattr](http://button.flattr.com/flattr-badge-large.png)](https://flattr.com/submit/auto?fid=w7r2ev&url=http%3A%2F%2Fegy1st.com)

[![TibMe](//egy1st.com/myhub/images/tib-btn.png)](https://tib.me/mytibs9YhLYtrVhQkmTdbDS51H54WyrxTx)

* * *

# Fork it at Github

You can fork the project at github to add your mother-tongue language, your contribution will help Number2text to be the most comprehensive digits-to-equavilant-language project. I could add 10 languages myself. I just ask you to add yours, so please [Contribute Now](https://github.com/egy1st/Number2Text)

