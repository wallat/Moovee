var $ = require('jquery');
var util = require('util');

regist_year = '2013';
parent_id = '156';

function handler(ghff_id) {
    return function (r) {
        var category = '';
        $(r.ghff_list).filter('option').each(function (i, e) {
            if (i == 0) {
                return;
            }
            if (e.value === ghff_id.toString()) {
                category = e.text;
            }
        });
        $(r.film_list).filter('option').each(function (i, e) {
            if (i == 0) {
                return;
            }
            film = e.text;
            util.puts(film + '	' + category);
        });
    };
}


// 157 - 174
for (var ghff_id = 157; ghff_id <= 174; ghff_id++) {
    // 取回次單元選單項目
    $.post(
        'http://www.goldenhorse.org.tw/ui/index.php?class=ghff&func=programme&header=0',
        {
            act_t: 'get_class_list',
            regist_year: regist_year,
            parent_id: parent_id,
            ghff_id: ghff_id,
        },
        handler(ghff_id),
        'json'
    );
}
