<div id="establishment-localization"></div>
<style>
    #establishment-localization { height: 700px; }
</style>
<?php $this->append('javascript'); ?>
var mapDetail = L.map('establishment-localization').setView([46.76306,2.42472], 6);
L.tileLayer('https://maps.wikimedia.org/osm-intl/{z}/{x}/{y}.png', {
attribution: 'Wikimedia Maps | Cartographie &copy; <a href="http://openstreetmap.org/copyright">contributeurs OpenStreetMap</a>',
maxZoom: 18,
}).addTo(mapDetail);
var marker = L.marker([0,0]).addTo(mapDetail);
var geojson = {"type":"FeatureCollection","features":[
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[4.230126967383267,43.46039468475486],[4.603425207465686,43.685844102068984],[4.840642965217014,43.98591631987798],[4.649488030457953,44.27015501345689],[5.061306209586474,44.30793868191089],[5.450606499151027,44.121515215522386],[5.6462009080442055,44.60937529165223],[6.307029478816334,45.00486046888334],[6.63000158139958,45.109445513318626],[6.74977602435639,44.907334006021],[7.041061402765247,44.71955796707601],[6.854506650744633,44.5288607026477],[6.88741993460796,44.36250807478695],[6.689386869212983,44.170235601283004],[6.848268210422537,43.95396090899771],[6.639193682375861,43.79556675719759],[6.212829892335351,43.799312216862525],[5.793992906397418,43.66080047239377],[5.671992258503504,43.17950249267158],[5.309170530590602,43.35972085932624],[4.833382062438532,43.33011079266784],[4.536676878425997,43.45145341715836],[4.230126967383267,43.46039468475486]]]},"properties":{"name":"Académie d'Aix-Marseille","vacances":"Zone B","wikipedia":"fr:Académie d'Aix-Marseille"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1.380142497106027,50.064989073046526],[1.628440884055441,50.363186864242415],[2.1597771269586827,50.194848788048105],[2.731897602413178,50.12537649499213],[2.87945433199348,50.03047639729706],[4.140545619777942,49.97899789193395],[4.233011996749745,49.957655982811964],[4.207521761573684,49.78109136904033],[3.9259221083897313,49.40710056115959],[3.6480189327684025,49.31597527305248],[3.620885858600743,48.96606620971323],[3.4850422620100754,48.851840699665416],[2.977227147180105,49.074331978062055],[2.5903342527454645,49.07992510849139],[2.313213509736604,49.18583979452928],[1.7044409622062764,49.23235250395127],[1.777164165863701,49.47443247366527],[1.7126680030728283,49.88613661618042],[1.380142497106027,50.064989073046526]]]},"properties":{"name":"Académie d'Amiens","vacances":"Zone B","wikipedia":"fr:Académie d'Amiens (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[5.373981373225502,47.60594660511162],[5.6910252237542585,47.68458557452979],[5.884916067542632,47.92575651459437],[6.6440009267872995,47.904767571968804],[6.845757329371895,47.82293736674359],[7.1302524323796215,47.50297636550475],[6.955666653541561,47.24377507272618],[6.4382444160740455,46.76159606167842],[6.110526603676324,46.576478200149616],[6.063857777193389,46.41639521096505],[5.878716883598453,46.269260514881005],[5.536948432010288,46.26841777837884],[5.310772226742186,46.44684038997344],[5.44113358481693,46.63807686298276],[5.261254393335666,46.9393926187223],[5.518275960683052,47.30208345962122],[5.373981373225502,47.60594660511162]]]},"properties":{"name":"Académie de Besançon","vacances":"Zone A","wikipedia":"fr:Académie de Besançon"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-0.7167857077982734,45.3277117173846],[-0.14189321104004213,45.090105406491276],[0.26980630063944583,45.30479890375132],[0.2762895318764648,45.42645839965202],[0.6293430692213803,45.71480056571494],[1.0232477636062163,45.61003707108498],[1.3232070049039464,45.38288000033283],[1.2334400653609225,45.22232817624308],[1.4481423589995508,45.01937925885029],[1.3158687571795025,44.740544862308454],[1.0215715072860492,44.54503256457842],[1.064235913726965,44.38033939634713],[0.6794487601498286,44.02950347632682],[0.21411650224185386,44.02301940120743],[-0.19002988469722873,43.92777058304022],[-0.19387611141771488,43.736802393849246],[-0.01756934015833801,43.26992035995146],[-0.31331072170254165,42.84940966745642],[-0.6021864591932767,42.83155767186776],[-0.7516764530965324,42.96716217465297],[-1.3541418391855282,43.02825260049122],[-1.3789241120861755,43.250078821271934],[-1.645861732697314,43.404957119779375],[-1.445443460559942,43.6466846569992],[-1.3074691499468711,44.16647661268667],[-1.1617739092983217,45.29745899278382],[-1.0656289312380491,45.5108499860871],[-0.7167857077982734,45.3277117173846]]]},"properties":{"name":"Académie de Bordeaux","vacances":"Zone A","wikipedia":"fr:Académie de Bordeaux (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[0.2961627812439842,49.42986527321509],[0.4204296989366751,48.88678237075904],[0.8160691299666742,48.670875116793916],[0.9675717992639999,48.523880772848756],[0.7981806183878887,48.19483630915845],[0.021520849430122956,48.38014837711624],[-0.20819319092142657,48.56418078933886],[-0.7645182294091346,48.4362880184191],[-1.0701089193914817,48.50881149517995],[-1.4822687278225524,48.48684446843762],[-1.570890495041381,48.62626310024911],[-1.6079050373343553,49.196989903816586],[-1.8286688930156365,49.38442698309922],[-1.8591389389692152,49.64869801392036],[-1.2887652375846903,49.69274489872758],[-1.1197350896212668,49.35903417190675],[-0.22656634359599473,49.285535106845636],[0.2961627812439842,49.42986527321509]]]},"properties":{"name":"Académie de Caen","vacances":"Zone B","wikipedia":"fr:Académie de Caen (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[2.062902806719234,44.97664246771834],[2.526795155058293,45.68512190157965],[2.3895819869854567,45.82369366178626],[2.599904903782474,46.03514879818211],[2.2811441930827074,46.420455810887],[2.614483123539279,46.55614800740112],[2.7286386942254492,46.74905646232133],[3.0320303982238,46.794659396490985],[3.196647033364816,46.68045765849395],[3.59783886133443,46.727811381489246],[3.9982344226361994,46.465445180716934],[3.90001550407948,46.27598890150259],[3.711828782682208,45.79960180348625],[4.02303798560908,45.3469203649469],[4.346857019842195,45.362063977001455],[4.483409027865919,45.23658931488736],[4.156922356565083,44.87331050669141],[3.8624809270685287,44.74384820844846],[3.7497828729308424,44.82342997629315],[3.1421491196861937,44.90301326029348],[2.9822622043475953,44.64514679339179],[2.850547624128855,44.8711195155559],[2.6303666852354386,44.8729174000277],[2.48711567064345,44.656536190641084],[2.2286490733300295,44.65511816579388],[2.062902806719234,44.97664246771834]]]},"properties":{"name":"Académie de Clermont-Ferrand","vacances":"Zone A","wikipedia":"fr:Académie de Clermont-Ferrand"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[8.539957766833794,42.23688518719643],[8.665434176225023,42.51324299931956],[9.124697685568073,42.731429806504906],[9.52611274488579,42.5684768204205],[9.548994272476767,42.104247114288796],[9.216265747255756,41.36761827401546],[8.822467503232087,41.54436257937049],[8.709497349394843,41.7579434630635],[8.741466953052202,42.04096600779778],[8.539957766833794,42.23688518719643]]]},"properties":{"name":"Académie de Corse","vacances":"","wikipedia":"fr:Académie de Corse"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[2.5903342527454645,49.07992510849139],[2.977227147180105,49.074331978062055],[3.4850422620100754,48.851840699665416],[3.414720883927801,48.39018216836531],[3.0461389787500677,48.35797068467962],[2.9365649956759636,48.163482262282955],[2.703213766570543,48.12429049883505],[2.4029340962109678,48.32068810657298],[2.5478781656189358,48.64973511272561],[2.331733357297069,48.81701126491228],[2.319889519433595,48.900458670139514],[2.5903342527454645,49.07992510849139]]]},"properties":{"name":"Académie de Créteil","vacances":"Zone C","wikipedia":"fr:Académie de Créteil"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[2.9365649956759636,48.163482262282955],[3.0461389787500677,48.35797068467962],[3.414720883927801,48.39018216836531],[3.926128181915909,47.93474081235834],[4.844639929242175,47.961278904024276],[4.979363775831187,47.68587979712445],[5.373981373225502,47.60594660511162],[5.518275960683052,47.30208345962122],[5.261254393335666,46.9393926187223],[5.44113358481693,46.63807686298276],[5.310772226742186,46.44684038997344],[4.932397247663095,46.5116851194115],[4.81104662070877,46.25863385846382],[4.41048019983996,46.295443098437474],[4.288773750201595,46.16991149548447],[3.90001550407948,46.27598890150259],[3.9982344226361994,46.465445180716934],[3.59783886133443,46.727811381489246],[3.196647033364816,46.68045765849395],[3.0320303982238,46.794659396490985],[3.077571479699052,47.029861670933364],[2.8504182667279414,47.53497287787203],[3.1284359775479955,47.9712270175201],[2.9365649956759636,48.163482262282955]]]},"properties":{"name":"Académie de Dijon","vacances":"Zone A","wikipedia":"fr:Académie de Dijon (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[3.8624809270685287,44.74384820844846],[4.156922356565083,44.87331050669141],[4.483409027865919,45.23658931488736],[5.352291371027379,45.88357768080683],[5.602741851902958,45.65195491678869],[5.776675184350163,45.7279867928896],[5.807736321760691,46.064915972897516],[5.955911271919525,46.132356497183885],[6.519123963185163,46.4563725878263],[6.821083393294513,46.4271523626027],[6.798047984295364,46.13615030007294],[7.0433186894111826,45.92658006333207],[6.818101974698048,45.8358763788701],[7.08105826196997,45.22449152010858],[6.63000158139958,45.109445513318626],[6.307029478816334,45.00486046888334],[5.6462009080442055,44.60937529165223],[5.450606499151027,44.121515215522386],[5.061306209586474,44.30793868191089],[4.649488030457953,44.27015501345689],[4.126110411814369,44.337430516759774],[3.8624809270685287,44.74384820844846]]]},"properties":{"name":"Académie de Grenoble","vacances":"Zone A","wikipedia":"fr:Académie de Grenoble"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-54.60277788449316,2.329472921472191],[-54.37472195449304,2.4847220323599988],[-54.20861088204536,2.7688890107870945],[-54.17805594529743,3.2061109469199462],[-54.008166828259334,3.5650433081724144],[-54.35472195283893,4.0269439482540985],[-54.486388922347615,4.898610968083531],[-54.29666689029825,5.240555949358637],[-54.007771839028905,5.5510725958388285],[-53.97472192141086,5.748056031707606],[-53.04891244037947,5.456503699534865],[-52.840894870096086,5.340401425442831],[-52.38040659769468,4.934209997967361],[-51.79479989454275,4.606050033721128],[-51.658666770936016,4.052761808451559],[-51.97103507007398,3.70695666137301],[-52.33527793276636,3.0644439735164393],[-52.67527787105468,2.373888972327063],[-52.964443944045236,2.1738890113982814],[-53.31583290316868,2.3449999498740954],[-53.45861095476358,2.2575000236925233],[-53.744999886053286,2.373888972327063],[-54.09972195420693,2.114999993615108],[-54.45888888638639,2.2088889781713807],[-54.60277788449316,2.329472921472191]]]},"properties":{"name":"Académie de Guyane","vacances":"","wikipedia":"fr:Académie de la Guyane"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-61.80976386945311,16.272699972343222],[-61.546458047704135,16.29373610727426],[-61.55529558363777,16.055191887765375],[-61.70032948457415,15.946639021261463],[-61.80976386945311,16.272699972343222]]]},"properties":{"name":"Académie de la Guadeloupe","vacances":"","wikipedia":"fr:Académie de la Guadeloupe"}},
{"type":"Feature","geometry":null,"properties":{"name":"Académie de la Martinique","vacances":"","wikipedia":"fr:Académie de la Martinique"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[55.21642675688027,-21.03903538810338],[55.448422787548076,-20.871915362559896],[55.66665339412813,-20.92673549929535],[55.83665048866339,-21.183341937013044],[55.775406406864086,-21.36538420210007],[55.33945175369267,-21.280890516922476],[55.21642675688027,-21.03903538810338]]]},"properties":{"name":"Académie de la Réunion","vacances":"","wikipedia":"fr:Académie de La Réunion"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[4.140545619777942,49.97899789193395],[2.87945433199348,50.03047639729706],[2.731897602413178,50.12537649499213],[2.1597771269586827,50.194848788048105],[1.628440884055441,50.363186864242415],[1.5807106980643184,50.86936599882269],[2.544854885530232,51.08984197104998],[2.7893656159156843,50.72887748188448],[3.1478503674683864,50.7901003798321],[3.288595621406852,50.525782598919655],[3.665490266729243,50.347046680909926],[4.200949147965895,50.27481966625922],[4.140545619777942,49.97899789193395]]]},"properties":{"name":"Académie de Lille","vacances":"Zone B","wikipedia":"fr:Académie de Lille"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[0.6293430692213803,45.71480056571494],[0.9255057606338362,46.01070699652284],[0.8021974351808291,46.209408073683015],[1.177145251619476,46.383991113695366],[2.2811441930827074,46.420455810887],[2.599904903782474,46.03514879818211],[2.3895819869854567,45.82369366178626],[2.526795155058293,45.68512190157965],[2.062902806719234,44.97664246771834],[1.8244017157530124,44.927851157560504],[1.4481423589995508,45.01937925885029],[1.2334400653609225,45.22232817624308],[1.3232070049039464,45.38288000033283],[1.0232477636062163,45.61003707108498],[0.6293430692213803,45.71480056571494]]]},"properties":{"name":"Académie de Limoges","vacances":"Zone A","wikipedia":"fr:Académie de Limoges"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[3.90001550407948,46.27598890150259],[4.288773750201595,46.16991149548447],[4.41048019983996,46.295443098437474],[4.81104662070877,46.25863385846382],[4.932397247663095,46.5116851194115],[5.310772226742186,46.44684038997344],[5.536948432010288,46.26841777837884],[5.878716883598453,46.269260514881005],[6.063857777193389,46.41639521096505],[5.955911271919525,46.132356497183885],[5.807736321760691,46.064915972897516],[5.776675184350163,45.7279867928896],[5.602741851902958,45.65195491678869],[5.352291371027379,45.88357768080683],[4.483409027865919,45.23658931488736],[4.346857019842195,45.362063977001455],[4.02303798560908,45.3469203649469],[3.711828782682208,45.79960180348625],[3.90001550407948,46.27598890150259]]]},"properties":{"name":"Académie de Lyon","vacances":"Zone A","wikipedia":"fr:Académie de Lyon (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[2.9822622043475953,44.64514679339179],[3.1421491196861937,44.90301326029348],[3.7497828729308424,44.82342997629315],[3.8624809270685287,44.74384820844846],[4.126110411814369,44.337430516759774],[4.649488030457953,44.27015501345689],[4.840642965217014,43.98591631987798],[4.603425207465686,43.685844102068984],[4.230126967383267,43.46039468475486],[3.889242997023847,43.50830580582639],[3.5089253213113194,43.27176055663878],[3.269036962051832,43.232905712206666],[3.0656015183696876,43.02108066659245],[3.0515371349553986,42.5444696214035],[2.660815710611369,42.36687166257924],[2.2569275893164704,42.438694785251215],[2.0120481005068864,42.353014668155794],[1.7863836654561478,42.57397227136165],[1.9490806008350003,42.73672151764816],[1.9494485507753756,43.120832383379565],[1.709336331347086,43.1997047829397],[1.8227221356662937,43.41668489920073],[2.109164247220821,43.39443767734414],[2.664826508691905,43.46448161211026],[2.61431675554866,43.59973860466095],[3.2074508017923358,43.81241380478052],[3.4398591591755454,43.99814498521484],[3.161184510388215,44.24482219247559],[2.9822622043475953,44.64514679339179]]]},"properties":{"name":"Académie de Montpellier","vacances":"Zone C","wikipedia":"fr:Académie de Montpellier (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[5.394098784687244,49.61691109554794],[6.552241434112569,49.42442278925176],[6.711325165755637,49.18845739722762],[7.066602572417918,49.11428151692058],[7.4397851094917415,49.183429267615935],[7.634579119466769,49.05415526454082],[7.287132147473102,48.79790989506641],[7.118938959859892,48.47440410457486],[7.1726329711907555,48.297283267029876],[6.845757329371895,47.82293736674359],[6.6440009267872995,47.904767571968804],[5.884916067542632,47.92575651459437],[5.730893623873352,48.193888373623196],[5.406463375920922,48.46507926018387],[5.005643001321292,48.6335261926511],[4.8888118883929,48.81725189064557],[5.0355352508841245,49.02458438646178],[5.113774200217571,49.561915210560954],[5.394098784687244,49.61691109554794]]]},"properties":{"name":"Académie de Nancy-Metz","vacances":"Zone B","wikipedia":"fr:Académie de Nancy-Metz"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-2.4576626972545514,47.44790118052646],[-2.0987689427529896,47.53375218400018],[-1.969086082212342,47.68993388472113],[-1.6603060136452572,47.70912606558032],[-1.0230415104169825,47.99424571332723],[-1.0701089193914817,48.50881149517995],[-0.7645182294091346,48.4362880184191],[-0.20819319092142657,48.56418078933886],[0.021520849430122956,48.38014837711624],[0.7981806183878887,48.19483630915845],[0.8475350482442277,47.94152989917279],[0.37927922324408603,47.569377569976936],[0.22588101886573997,47.52642608953794],[0.05379058022390645,47.16346660860145],[-0.8430596414987442,46.99009436174378],[-0.6578375402021231,46.69960246280377],[-0.6030507283281281,46.361489581796896],[-1.131667950529397,46.30489681902719],[-1.813912627169519,46.494258415343715],[-2.144313437826321,46.82186270773178],[-1.9812749629764461,47.02716469636731],[-2.4576626972545514,47.44790118052646]]]},"properties":{"name":"Académie de Nantes","vacances":"Zone B","wikipedia":"fr:Académie de Nantes"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[6.88741993460796,44.36250807478695],[7.357118633617455,44.11664406097145],[7.718477634753226,44.08271741725187],[7.530153021959842,43.78755860294668],[7.15778831112068,43.65389376841557],[6.7340314320346835,43.406697815453974],[6.493776110981633,43.15147978057291],[6.125733015311314,43.07814502070991],[5.671992258503504,43.17950249267158],[5.793992906397418,43.66080047239377],[6.212829892335351,43.799312216862525],[6.639193682375861,43.79556675719759],[6.848268210422537,43.95396090899771],[6.689386869212983,44.170235601283004],[6.88741993460796,44.36250807478695]]]},"properties":{"name":"Académie de Nice","vacances":"Zone B","wikipedia":"fr:Académie de Nice"}},
{"type":"Feature","geometry":null,"properties":{"name":"Académie de Paris","vacances":"Zone C","wikipedia":"fr:Académie de Paris"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-1.131667950529397,46.30489681902719],[-0.6030507283281281,46.361489581796896],[-0.6578375402021231,46.69960246280377],[-0.8430596414987442,46.99009436174378],[0.05379058022390645,47.16346660860145],[0.6920112502405978,46.97449046141746],[1.1460244660740815,46.50578166280039],[1.177145251619476,46.383991113695366],[0.8021974351808291,46.209408073683015],[0.9255057606338362,46.01070699652284],[0.6293430692213803,45.71480056571494],[0.2762895318764648,45.42645839965202],[0.26980630063944583,45.30479890375132],[-0.14189321104004213,45.090105406491276],[-0.7167857077982734,45.3277117173846],[-0.916958739863081,45.55063166257752],[-1.2379029855239567,45.70994790764848],[-1.0818965227181696,45.898169870825996],[-1.131667950529397,46.30489681902719]]]},"properties":{"name":"Académie de Poitiers","vacances":"Zone A","wikipedia":"fr:Académie de Poitiers"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[3.4850422620100754,48.851840699665416],[3.620885858600743,48.96606620971323],[3.6480189327684025,49.31597527305248],[3.9259221083897313,49.40710056115959],[4.207521761573684,49.78109136904033],[4.233011996749745,49.957655982811964],[4.684718967754309,49.99680597196403],[5.394098784687244,49.61691109554794],[5.113774200217571,49.561915210560954],[5.0355352508841245,49.02458438646178],[4.8888118883929,48.81725189064557],[5.005643001321292,48.6335261926511],[5.406463375920922,48.46507926018387],[5.730893623873352,48.193888373623196],[5.884916067542632,47.92575651459437],[5.6910252237542585,47.68458557452979],[5.373981373225502,47.60594660511162],[4.979363775831187,47.68587979712445],[4.844639929242175,47.961278904024276],[3.926128181915909,47.93474081235834],[3.414720883927801,48.39018216836531],[3.4850422620100754,48.851840699665416]]]},"properties":{"name":"Académie de Reims","vacances":"Zone B","wikipedia":"fr:Académie de Reims"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[-1.570890495041381,48.62626310024911],[-1.4822687278225524,48.48684446843762],[-1.0701089193914817,48.50881149517995],[-1.0230415104169825,47.99424571332723],[-1.6603060136452572,47.70912606558032],[-1.969086082212342,47.68993388472113],[-2.0987689427529896,47.53375218400018],[-2.4576626972545514,47.44790118052646],[-2.7820263800444285,47.621337192699],[-3.160896151182012,47.606383895065896],[-3.5229763198278743,47.761908972647255],[-4.387335645533791,47.92632758595053],[-4.270678985433576,48.13439267105744],[-4.749988939330922,48.54395346354483],[-4.32945099340251,48.67830356121339],[-3.722887313324304,48.70638370179314],[-3.086571251373003,48.86662521267935],[-2.675985560814295,48.508279379285455],[-2.4388134374793995,48.653213980764725],[-1.9722849829390916,48.684763505440294],[-1.570890495041381,48.62626310024911]]]},"properties":{"name":"Académie de Rennes","vacances":"Zone B","wikipedia":"fr:Académie de Rennes"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1.380142497106027,50.064989073046526],[1.7126680030728283,49.88613661618042],[1.777164165863701,49.47443247366527],[1.7044409622062764,49.23235250395127],[1.5014234147943046,48.94101916779435],[1.327562306895943,48.76036740101942],[0.8160691299666742,48.670875116793916],[0.4204296989366751,48.88678237075904],[0.2961627812439842,49.42986527321509],[0.20945819850452368,49.71381380130578],[1.025431568061911,49.91627661041284],[1.380142497106027,50.064989073046526]]]},"properties":{"name":"Académie de Rouen","vacances":"Zone B","wikipedia":"fr:Académie de Rouen (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[6.845757329371895,47.82293736674359],[7.1726329711907555,48.297283267029876],[7.118938959859892,48.47440410457486],[7.287132147473102,48.79790989506641],[7.634579119466769,49.05415526454082],[8.19536662895987,48.95686999110734],[7.803088693723047,48.59030759813552],[7.5769150935692675,48.118020093363],[7.621896883948628,47.97249919704417],[7.386300854779719,47.43235399780638],[7.1302524323796215,47.50297636550475],[6.845757329371895,47.82293736674359]]]},"properties":{"name":"Académie de Strasbourg","vacances":"Zone B","wikipedia":"fr:Académie de Strasbourg"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1.4481423589995508,45.01937925885029],[1.8244017157530124,44.927851157560504],[2.062902806719234,44.97664246771834],[2.2286490733300295,44.65511816579388],[2.48711567064345,44.656536190641084],[2.6303666852354386,44.8729174000277],[2.850547624128855,44.8711195155559],[2.9822622043475953,44.64514679339179],[3.161184510388215,44.24482219247559],[3.4398591591755454,43.99814498521484],[3.2074508017923358,43.81241380478052],[2.61431675554866,43.59973860466095],[2.664826508691905,43.46448161211026],[2.109164247220821,43.39443767734414],[1.8227221356662937,43.41668489920073],[1.709336331347086,43.1997047829397],[1.9494485507753756,43.120832383379565],[1.9490806008350003,42.73672151764816],[1.7863836654561478,42.57397227136165],[0.7086465221665085,42.861522594656044],[0.674912088301968,42.69125399850963],[0.0017359044550325628,42.68580606994985],[-0.31331072170254165,42.84940966745642],[-0.01756934015833801,43.26992035995146],[-0.19387611141771488,43.736802393849246],[-0.19002988469722873,43.92777058304022],[0.21411650224185386,44.02301940120743],[0.6794487601498286,44.02950347632682],[1.064235913726965,44.38033939634713],[1.0215715072860492,44.54503256457842],[1.3158687571795025,44.740544862308454],[1.4481423589995508,45.01937925885029]]]},"properties":{"name":"Académie de Toulouse","vacances":"Zone C","wikipedia":"fr:Académie de Toulouse"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1.7044409622062764,49.23235250395127],[2.313213509736604,49.18583979452928],[2.5903342527454645,49.07992510849139],[2.319889519433595,48.900458670139514],[2.331733357297069,48.81701126491228],[2.5478781656189358,48.64973511272561],[2.4029340962109678,48.32068810657298],[2.0407181022941465,48.28458510610686],[1.5787435672551535,48.70216849639466],[1.5014234147943046,48.94101916779435],[1.7044409622062764,49.23235250395127]]]},"properties":{"name":"Académie de Versailles","vacances":"Zone C","wikipedia":"fr:Académie de Versailles (éducation)"}},
{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[0.7981806183878887,48.19483630915845],[0.9675717992639999,48.523880772848756],[0.8160691299666742,48.670875116793916],[1.327562306895943,48.76036740101942],[1.5014234147943046,48.94101916779435],[1.5787435672551535,48.70216849639466],[2.0407181022941465,48.28458510610686],[2.4029340962109678,48.32068810657298],[2.703213766570543,48.12429049883505],[2.9365649956759636,48.163482262282955],[3.1284359775479955,47.9712270175201],[2.8504182667279414,47.53497287787203],[3.077571479699052,47.029861670933364],[3.0320303982238,46.794659396490985],[2.7286386942254492,46.74905646232133],[2.614483123539279,46.55614800740112],[2.2811441930827074,46.420455810887],[1.177145251619476,46.383991113695366],[1.1460244660740815,46.50578166280039],[0.6920112502405978,46.97449046141746],[0.05379058022390645,47.16346660860145],[0.22588101886573997,47.52642608953794],[0.37927922324408603,47.569377569976936],[0.8475350482442277,47.94152989917279],[0.7981806183878887,48.19483630915845]]]},"properties":{"name":"Académie d'Orléans-Tours","vacances":"Zone B","wikipedia":"fr:Académie d'Orléans-Tours"}}
]}
var myStyle = {
"color": "#ff7800",
"weight": 2,
"opacity": 0.85
};

L.geoJson(geojson,{
style: function(feature) {
    switch (feature.properties.vacances) {
        case 'Zone A': return {color: "#FEA347"};
        case 'Zone B':   return {color: "#87CEEB"};
        case 'Zone C':   return {color: "#7FDD4C"};
    }
}
}).addTo(mapDetail);

mapDetail.on('click', function(e) {
console.log(e);
});

<?php $this->end(); ?>