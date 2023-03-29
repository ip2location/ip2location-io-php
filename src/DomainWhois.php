<?php

namespace IP2LocationIO;

use Exception;

/**
 * IP2WHOIS Domain WHOIS module.
 */
class DomainWhois extends \Exception
{

	private $iplIOApiKey = '';

	public function __construct($config)
	{
		$this->iplIOApiKey = $config->apiKey;
	}

	/**
	 * Lookup domain WHOIS information.
	 *
	 * @param array $params parameters of lookup details
	 *
	 * @return object IP2WHOIS Domain WHOIS result in JSON object
	 */
	public function lookup($domain)
	{
		$queries = [
			'key'            => $this->iplIOApiKey,
			'format'         => 'json',
			'domain'         => (isset($domain)) ? $domain : '',
			'source'         => 'sdk-php-iplio',
			'source_version' => Configuration::VERSION,
		];

		$http = new Http();
		$response = $http->get('https://api.ip2whois.com/v2?', $queries);

		if (($json = json_decode($response)) === null) {
			// return false;
			throw new Exception('DomainWhois lookup error.', 10005);
		}

		if (isset($json->error)) {
			throw new Exception($json->error->error_message, $json->error->error_code);
		} else {
			return $json;
		}
	}

	/**
	 * Get Punycode.
	 *
	 * @param array $params parameters of getPunycode details
	 *
	 * @return object result in text
	 */
	public function getPunycode($domain)
	{
		$result  = (isset($domain)) ? $domain : '';

		return idn_to_ascii($result);
	}

	/**
	 * Get Normal Text.
	 *
	 * @param array $params parameters of getNormalText details
	 *
	 * @return object result in text
	 */
	public function getNormalText($domain)
	{
		$result  = (isset($domain)) ? $domain : '';

		return idn_to_utf8($result);
	}

	/**
	 * Get domain name from a URL.
	 *
	 * @param array $params parameters of getDomainName details
	 *
	 * @return object result in text
	 */
	public function getDomainName($url)
	{
		$tlds = [".aaa",".aarp",".abarth",".abbott",".abbvie",".abc",".able",".abogado",".abudhabi",".academy",".accountant",".accountants",".aco",".actor",".adac",".ads",".adult",".aeg",".aero",".aetna",".afamilycompany",".afl",".agakhan",".agency",".aig",".airbus",".airforce",".airtel",".akdn",".alfaromeo",".alibaba",".alipay",".allfinanz",".allstate",".ally",".alsace",".americanexpress",".americanfamily",".amex",".amfam",".amica",".amsterdam",".analytics",".android",".anquan",".anz",".apartments",".app",".apple",".aquarelle",".arab",".aramco",".archi",".army",".art",".arte",".asda",".asia",".associates",".athleta",".attorney",".auction",".audi",".audible",".audio",".auspost",".author",".auto",".autos",".avianca",".aws",".axa",".azure",".baby",".baidu",".band",".bank",".bar",".barcelona",".barclaycard",".barclays",".barefoot",".bargains",".bauhaus",".bayern",".bbc",".bbt",".bbva",".bcg",".bcn",".beats",".beauty",".beer",".bentley",".berlin",".best",".bestbuy",".bet",".bible",".bid",".bike",".bingo",".bio",".biz",".black",".blackfriday",".blockbuster",".blog",".blue",".bms",".bmw",".bnpparibas",".boats",".boehringer",".bofa",".bom",".bond",".boo",".book",".bosch",".bostik",".bot",".boutique",".bradesco",".bridgestone",".broadway",".broker",".brother",".brussels",".bugatti",".build",".builders",".business",".buy",".buzz",".bzh",".cab",".cafe",".cal",".call",".cam",".camera",".camp",".cancerresearch",".canon",".capetown",".capital",".capitalone",".car",".cards",".care",".career",".careers",".cars",".casa",".cash",".casino",".cat",".catering",".cba",".cbs",".ceb",".center",".ceo",".cern",".cfa",".cfd",".chanel",".channel",".chat",".cheap",".chintai",".christmas",".chrome",".chrysler",".church",".cipriani",".city",".cityeats",".claims",".cleaning",".click",".clinic",".clinique",".clothing",".cloud",".club",".clubmed",".coach",".codes",".coffee",".college",".cologne",".com",".comcast",".commbank",".community",".company",".compare",".computer",".comsec",".condos",".construction",".consulting",".contact",".contractors",".cooking",".cookingchannel",".cool",".coop",".corsica",".country",".coupon",".coupons",".credit",".creditcard",".creditunion",".cricket",".cruises",".csc",".cuisinella",".cymru",".cyou",".dabur",".dad",".dance",".date",".dating",".datsun",".day",".dclk",".dds",".deal",".deals",".degree",".delivery",".dell",".deloitte",".delta",".democrat",".dental",".dentist",".desi",".design",".dev",".dhl",".diamonds",".diet",".digital",".direct",".directory",".discount",".dish",".diy",".dk",".dnp",".docs",".doctor",".dodge",".dog",".domains",".dot",".download",".drive",".dtv",".dubai",".duck",".dunlop",".durban",".dvag",".earth",".eat",".eco",".edeka",".edu",".education",".email",".emerck",".energy",".engineer",".engineering",".enterprises",".epson",".equipment",".ericsson",".erni",".esq",".estate",".esurance",".eurovision",".eus",".events",".everbank",".exchange",".expert",".exposed",".express",".extraspace",".fage",".fail",".fairwinds",".faith",".family",".fan",".fans",".farm",".farmers",".fashion",".fast",".fedex",".feedback",".ferrari",".ferrero",".fiat",".fidelity",".film",".final",".finance",".financial",".fire",".firestone",".firmdale",".fish",".fishing",".fit",".fitness",".flickr",".flights",".flir",".florist",".flowers",".fly",".foo",".foodnetwork",".football",".ford",".forex",".forsale",".forum",".foundation",".fox",".free",".fresenius",".frl",".frogans",".frontdoor",".frontier",".ftr",".fujitsu",".fujixerox",".fun",".fund",".furniture",".futbol",".fyi",".gal",".gallery",".gallo",".gallup",".game",".games",".gap",".garden",".gbiz",".gdn",".gea",".gent",".genting",".george",".ggee",".gift",".gifts",".gives",".giving",".glade",".glass",".gle",".global",".globo",".gmail",".gmbh",".gmo",".gmx",".godaddy",".gold",".goldpoint",".golf",".goo",".goodyear",".goog",".google",".gop",".got",".grainger",".graphics",".gratis",".green",".gripe",".group",".guge",".guide",".guitars",".guru",".hamburg",".hangout",".haus",".hbo",".hdfc",".hdfcbank",".health",".healthcare",".help",".helsinki",".here",".hermes",".hgtv",".hiphop",".hisamitsu",".hitachi",".hiv",".hkt",".hockey",".holdings",".holiday",".homedepot",".homegoods",".homes",".homesense",".honda",".horse",".hospital",".host",".hosting",".hot",".hoteles",".house",".how",".hsbc",".hughes",".hyundai",".ibm",".icbc",".ice",".icu",".ieee",".ifm",".ikano",".imamat",".imdb",".immo",".immobilien",".industries",".infiniti",".info",".ing",".ink",".institute",".insurance",".insure",".int",".international",".intuit",".investments",".irish",".iselect",".ismaili",".ist",".istanbul",".it",".itau",".itv",".jaguar",".java",".jcb",".jcp",".jeep",".jetzt",".jewelry",".jll",".jmp",".jnj",".jobs",".joburg",".jot",".joy",".jpmorgan",".jprs",".juegos",".juniper",".kaufen",".kddi",".kerryhotels",".kerrylogistics",".kerryproperties",".kfh",".kia",".kim",".kinder",".kindle",".kitchen",".kiwi",".koeln",".komatsu",".kosher",".kpmg",".kpn",".kred",".kuokgroup",".kyoto",".lacaixa",".ladbrokes",".lamborghini",".lamer",".lancaster",".lancia",".lancome",".land",".landrover",".lasalle",".lat",".latino",".latrobe",".law",".lawyer",".lds",".lease",".leclerc",".lefrak",".legal",".lego",".lexus",".lgbt",".liaison",".lidl",".life",".lifeinsurance",".lifestyle",".lighting",".like",".lilly",".limited",".limo",".lincoln",".linde",".link",".lipsy",".live",".lixil",".loan",".loans",".locker",".locus",".loft",".logs",".lol",".london",".lotte",".lotto",".love",".lpl",".lplfinancial",".ltd",".ltda",".lundbeck",".luxe",".luxury",".macys",".madrid",".maison",".makeup",".man",".management",".mango",".market",".marketing",".markets",".marriott",".maserati",".mba",".mckinsey",".med",".media",".meet",".meme",".memorial",".men",".menu",".metlife",".miami",".mini",".mit",".mitsubishi",".mlb",".mls",".mma",".mobi",".moda",".moe",".moi",".mom",".monash",".money",".mopar",".mormon",".mortgage",".moscow",".motorcycles",".mov",".movie",".movistar",".mtn",".mtr",".museum",".mutual",".nab",".nadex",".nagoya",".name",".nationwide",".natura",".navy",".nba",".nec",".net",".netbank",".netflix",".network",".neustar",".new",".news",".next",".nextdirect",".nexus",".nfl",".ngo",".nhk",".nico",".nike",".nikon",".ninja",".nissan",".nissay",".nokia",".northwesternmutual",".norton",".now",".nowruz",".nowtv",".nra",".nrw",".ntt",".nyc",".obi",".off",".okinawa",".olayan",".olayangroup",".oldnavy",".ollo",".omega",".one",".ong",".onl",".online",".onyourside",".ooo",".oracle",".orange",".org",".organic",".origins",".osaka",".otsuka",".ott",".ovh",".page",".panasonic",".paris",".pars",".partners",".parts",".party",".passagens",".pay",".pccw",".pet",".pfizer",".philips",".photo",".photography",".photos",".physio",".pics",".pictures",".pid",".pin",".ping",".pink",".pioneer",".pizza",".pl",".place",".play",".playstation",".plumbing",".plus",".pnc",".pohl",".poker",".politie",".porn",".post",".pramerica",".praxi",".press",".prime",".pro",".prod",".productions",".prof",".progressive",".promo",".properties",".property",".protection",".pru",".prudential",".pub",".pwc",".qpon",".quebec",".quest",".qvc",".racing",".radio",".raid",".read",".realestate",".realtor",".realty",".recipes",".red",".redstone",".redumbrella",".rehab",".reise",".reisen",".reit",".rent",".rentals",".repair",".report",".republican",".rest",".restaurant",".review",".reviews",".rexroth",".rich",".richardli",".ricoh",".rightathome",".rio",".rip",".rocher",".rocks",".rodeo",".room",".rsvp",".ruhr",".run",".rwe",".ryukyu",".saarland",".safe",".sakura",".sale",".salon",".samsclub",".samsung",".sandvik",".sandvikcoromant",".sanofi",".sap",".sarl",".sas",".save",".sbi",".sbs",".sca",".scb",".schaeffler",".schmidt",".scholarships",".school",".schule",".schwarz",".science",".scjohnson",".scor",".scot",".seat",".secure",".security",".seek",".select",".services",".ses",".seven",".sew",".sex",".sexy",".sfr",".sh",".shangrila",".sharp",".shell",".shia",".shiksha",".shoes",".shop",".shopping",".shouji",".show",".showtime",".shriram",".silk",".sina",".singles",".site",".ski",".skin",".sky",".skype",".sling",".smart",".smile",".sncf",".soccer",".social",".softbank",".software",".solar",".solutions",".song",".sony",".soy",".space",".spot",".spreadbetting",".srl",".srt",".stada",".staples",".star",".starhub",".statebank",".statefarm",".stc",".stcgroup",".stockholm",".storage",".store",".stream",".studio",".study",".style",".sucks",".supplies",".supply",".support",".surf",".surgery",".suzuki",".swatch",".swiftcover",".swiss",".sydney",".symantec",".systems",".tab",".taipei",".talk",".taobao",".target",".tatamotors",".tatar",".tattoo",".tax",".taxi",".tci",".tdk",".team",".tech",".technology",".tel",".telefonica",".temasek",".tennis",".teva",".thd",".theater",".theatre",".tiaa",".tickets",".tienda",".tiffany",".tips",".tires",".tirol",".tjmaxx",".tjx",".tkmaxx",".tmall",".today",".tokyo",".tools",".top",".toray",".toshiba",".total",".tours",".town",".toyota",".toys",".trade",".trading",".training",".travel",".travelchannel",".travelers",".travelersinsurance",".trust",".trv",".tube",".tui",".tunes",".tushu",".tvs",".ubank",".ubs",".uconnect",".university",".uno",".uol",".ups",".us",".vacations",".vana",".vanguard",".vegas",".ventures",".verisign",".versicherung",".vet",".viajes",".video",".vig",".viking",".villas",".vin",".vip",".virgin",".visa",".vision",".vistaprint",".viva",".vivo",".vlaanderen",".vodka",".volkswagen",".volvo",".vote",".voting",".voto",".voyage",".vuelos",".wales",".walmart",".walter",".wang",".wanggou",".warman",".watch",".watches",".weather",".weatherchannel",".webcam",".weber",".website",".wed",".wedding",".weibo",".whoswho",".wien",".wiki",".williamhill",".win",".wine",".winners",".wme",".wolterskluwer",".woodside",".work",".works",".world",".wow",".wtc",".wtf",".xerox",".xfinity",".xihuan",".xin",".xxx",".xyz",".yachts",".yamaxun",".yodobashi",".yoga",".yokohama",".you",".youtube",".yun",".zappos",".zara",".zero",".zip",".zone",".zuerich",".xn--1qqw23a",".xn--30rr7y",".xn--3bst00m",".xn--3ds443g",".xn--3pxu8k",".xn--55qw42g",".xn--55qx5d",".xn--5tzm5g",".xn--6frz82g",".xn--9et52u",".xn--9krt00a",".xn--czrs0t",".xn--czru2d",".xn--efvy88h",".xn--estv75g",".xn--fct429k",".xn--fiq64b",".xn--fjq720a",".xn--flw351e",".xn--g2xx48c",".xn--hxt814e",".xn--io0a7i",".xn--jvr189m",".xn--kput3i",".xn--mxtq1m",".xn--nqv7f",".xn--nyqy26a",".xn--pbt977c",".xn--pssy2u",".xn--rhqv96g",".xn--rovu88b",".xn--unup4y",".xn--vhquv",".xn--vuq861b",".xn--w4rs40l",".xn--xhq521b",".xn--zfr164b",".xn--6qq986b3xl",".xn--b4w605ferd",".xn--fiq228c5hs",".xn--jlq61u9w7b",".xn--kcrx77d1x4a",".xn--3oq18vl8pn36a",".xn--5su34j936bgsg",".xn--fzys8d69uvgm",".xn--nqv7fs00ema",".xn--w4r85el8fhu5dnra",".xn--11b4c3d",".xn--c2br7g",".xn--42c2d9a",".xn--i1b6b1a6a2e",".xn--4gbrim",".xn--fhbei",".xn--mgba3a3ejt",".xn--mgba7c0bbn0a",".xn--mgbaakc7dvf",".xn--mgbab2bd",".xn--mgbt3dhd",".xn--ngbc5azd",".xn--ngbe9e0a",".xn--ngbrx",".xn--node",".xn--y9a3aq",".xn--80adxhks",".xn--80asehdb",".xn--80aswg",".xn--d1acj3b",".xn--d1alf",".xn--j1aef",".xn--9dbq2a",".xn--c1avg",".xn--p1acf",".xn--vermgensberater-ctb",".xn--vermgensberatung-pwb",".xn--bck1b9a5dre4c",".xn--cck2b3b",".xn--eckvdtc9d",".xn--gckr3f0f",".xn--q9jyb4c",".xn--qcka1pmc",".xn--tckwe",".xn--mk1bu44c",".xn--cg4bki",".a.se",".ab.ca",".ac",".ac.cn",".ac.cr",".ac.id",".ac.il",".ac.in",".ac.jp",".ac.mu",".ac.mw",".ac.rs",".ac.rw",".ac.tz",".ac.ug",".ac.uk",".ac.za",".ac.zm",".act.edu.au",".act.gov.au",".ad",".ad.jp",".adm.br",".adv.br",".ae",".af",".ag",".agr.br",".ai",".aichi.jp",".akita.jp",".al",".am",".am.br",".ao",".aomori.jp",".aq",".ar",".arq.br",".art.br",".as",".asso.fr",".at",".ath.cx",".au",".aw",".ax",".az",".ba",".bb",".bc.ca",".bd",".be",".bf",".bg",".bg.ac.rs",".bg.it",".bh",".bi",".bio.br",".biz.id",".biz.pl",".biz.pr",".biz.tr",".biz.ua",".bj",".bl",".blog.br",".bm",".bn",".bo",".bo.it",".bq",".br",".br.it",".brantford.on.ca",".bs",".bt",".bv",".bw",".by",".bydgoszcz.pl",".bz",".ca",".cc",".cd",".cf",".ch",".chiba.jp",".ci",".ck",".cl",".cm",".cn",".cnt.br",".co",".co.bw",".co.ci",".co.cm",".co.cr",".co.gy",".co.hu",".co.id",".co.il",".co.im",".co.in",".co.jp",".co.ke",".co.kr",".co.ma",".co.mu",".co.mw",".co.mz",".co.nl",".co.rs",".co.rw",".co.th",".co.tz",".co.ua",".co.ug",".co.uk",".co.za",".co.zm",".co.zw",".com.af",".com.ag",".com.ai",".com.ar",".com.au",".com.bo",".com.br",".com.cm",".com.cn",".com.co",".com.do",".com.dz",".com.ee",".com.fr",".com.ge",".com.gi",".com.gy",".com.hk",".com.hn",".com.hr",".com.ht",".com.im",".com.kg",".com.ky",".com.kz",".com.lc",".com.ly",".com.mg",".com.mk",".com.mx",".com.my",".com.ng",".com.pl",".com.pr",".com.ps",".com.pt",".com.ro",".com.sa",".com.sb",".com.sg",".com.so",".com.sy",".com.tn",".com.tr",".com.tw",".com.ua",".com.vc",".coop.br",".cr",".cu",".cv",".cw",".cx",".cy",".cz",".czest.pl",".de",".desa.id",".dj",".dk",".dm",".dn.ua",".do",".dp.ua",".dz",".ec",".ed.cr",".ed.jp",".edu.af",".edu.au",".edu.bi",".edu.bo",".edu.br",".edu.co",".edu.dm",".edu.do",".edu.dz",".edu.ge",".edu.hk",".edu.hn",".edu.ht",".edu.in",".edu.ky",".edu.kz",".edu.lr",".edu.ly",".edu.mg",".edu.mx",".edu.my",".edu.ng",".edu.pl",".edu.pr",".edu.ps",".edu.pt",".edu.sa",".edu.sb",".edu.sg",".edu.ua",".edu.uy",".ee",".eg",".ehime.jp",".eng.br",".ens.tn",".er",".es",".esp.br",".et",".eti.br",".far.br",".fhsk.se",".fi",".fi.cr",".fi.it",".fj",".fj.cn",".fk",".fm",".fm.br",".fo",".fr",".from.hr",".fukui.jp",".fukuoka.jp",".fukushima.jp",".g12.br",".ga",".gb",".gd",".gd.cn",".gda.pl",".ge",".gen.in",".gen.tr",".gf",".gg",".gh",".gi",".gifu.jp",".gl",".gm",".gn",".go.cr",".go.id",".go.jp",".go.th",".go.tz",".go.ug",".gob.hn",".gob.mx",".gouv.ci",".gouv.fr",".gouv.ht",".gov.af",".gov.ag",".gov.ar",".gov.au",".gov.br",".gov.by",".gov.co",".gov.cx",".gov.dm",".gov.dz",".gov.ge",".gov.gi",".gov.hk",".gov.ie",".gov.il",".gov.in",".gov.it",".gov.ky",".gov.kz",".gov.lc",".gov.ma",".gov.mg",".gov.mw",".gov.my",".gov.ng",".gov.pl",".gov.pr",".gov.ps",".gov.pt",".gov.rw",".gov.sa",".gov.sb",".gov.sc",".gov.sg",".gov.sy",".gov.tn",".gov.ua",".gov.uk",".gov.zm",".gp",".gq",".gr",".gr.jp",".gs",".gt",".gu",".gub.uy",".gunma.jp",".gw",".gy",".hiroshima.jp",".hk",".hm",".hn",".hokkaido.jp",".hr",".ht",".hu",".hyogo.jp",".ibaraki.jp",".id",".id.au",".id.lv",".idv.hk",".idv.tw",".ie",".il",".im",".imb.br",".in",".in.th",".ind.br",".ind.in",".inf.br",".info.ke",".info.pl",".info.ro",".info.tn",".info.tr",".io",".iq",".ir",".is",".isla.pr",".it",".iwate.jp",".je",".jm",".jo",".jor.br",".jp",".jus.br",".kagoshima.jp",".kalisz.pl",".kanagawa.jp",".katowice.pl",".ke",".kg",".kh",".ki",".kiev.ua",".km",".kn",".kommune.no",".koriyama.fukushima.jp",".kp",".kr",".krakow.pl",".kuleuven.be",".kumamoto.jp",".kw",".ky",".kyoto.jp",".kz",".la",".lb",".lc",".leg.br",".lel.br",".lg.jp",".li",".lk",".lm.lt",".lodz.pl",".longueuil.qc.ca",".lr",".ls",".lt",".ltd.gi",".ltd.uk",".lu",".lv",".ly",".ma",".malopolska.pl",".mb.ca",".mc",".md",".me",".me.uk",".med.br",".med.sa",".media.pl",".mf",".mg",".mh",".mie.jp",".mil.ar",".mil.br",".mil.by",".mil.co",".mil.hn",".mil.pl",".mil.uy",".miyagi.jp",".miyazaki.jp",".mk",".mn",".mo",".mp",".mq",".mr",".ms",".msk.ru",".mt",".mu",".mus.br",".mv",".mw",".mx",".my",".my.id",".mz",".na",".nagano.jp",".nagasaki.jp",".nara.jp",".nat.tn",".nb.ca",".nc",".ne",".ne.jp",".ne.kr",".net.ae",".net.al",".net.ar",".net.au",".net.bo",".net.br",".net.cn",".net.co",".net.do",".net.ge",".net.gy",".net.hk",".net.il",".net.in",".net.kg",".net.lr",".net.ly",".net.ma",".net.mu",".net.mx",".net.nf",".net.ng",".net.pl",".net.pr",".net.rw",".net.sa",".net.sb",".net.sg",".net.so",".net.sy",".net.tn",".net.tr",".net.tw",".net.ua",".net.uk",".net.uy",".net.za",".nf",".nf.ca",".ng",".ni",".nic.in",".niigata.jp",".nl",".nl.ca",".no",".nom.br",".nom.co",".not.br",".np",".nr",".ns.ca",".nsw.edu.au",".nsw.gov.au",".nt.ca",".nt.edu.au",".nu",".nysa.pl",".nz",".off.ai",".oita.jp",".okayama.jp",".okinawa.jp",".olsztyn.pl",".om",".on.ca",".or.cr",".or.id",".or.jp",".or.ke",".or.kr",".or.tz",".or.ug",".org.af",".org.ar",".org.au",".org.br",".org.bw",".org.bz",".org.cn",".org.co",".org.do",".org.dz",".org.ge",".org.gg",".org.hk",".org.hn",".org.ht",".org.il",".org.im",".org.in",".org.kg",".org.ky",".org.kz",".org.lb",".org.ly",".org.ma",".org.mk",".org.mw",".org.mx",".org.my",".org.mz",".org.ng",".org.pl",".org.ps",".org.ro",".org.rs",".org.sa",".org.sb",".org.sg",".org.so",".org.tn",".org.ua",".org.ug",".org.uk",".org.uy",".org.za",".org.zm",".osaka.jp",".oslo.no",".pa",".pe",".pe.ca",".pe.kr",".pf",".pg",".ph",".pi.it",".pila.pl",".pk",".pl",".plc.uk",".pm",".pn",".poznan.pl",".pp.ru",".pp.se",".pp.ua",".ppg.br",".pr",".pr.it",".prd.fr",".pri.ee",".pro.br",".ps",".pt",".pub.sa",".pvt.ge",".pw",".py",".qa",".qc.ca",".qld.edu.au",".qld.gov.au",".radom.pl",".re",".re.it",".res.in",".rm.it",".ro",".roma.it",".rs",".ru",".rw",".rzeszow.pl",".sa",".sa.cr",".sa.edu.au",".sa.gov.au",".saitama.jp",".saskatoon.sk.ca",".sb",".sc",".sc.cn",".sch.uk",".sd",".se",".seoul.kr",".sg",".sh",".sh.cn",".shiga.jp",".shimane.jp",".shizuoka.jp",".si",".sj",".sk",".sk.ca",".sl",".sld.do",".sm",".sm.ua",".sn",".so",".sr",".srv.br",".ss",".st",".store.ro",".su",".suginami.tokyo.jp",".sv",".sx",".sx.cn",".sy",".sz",".szczecin.pl",".tas.edu.au",".tc",".td",".telenet.be",".tf",".tg",".th",".tj",".tk",".tl",".tm",".tm.fr",".tmp.br",".tn",".tn.it",".to",".to.it",".tokushima.jp",".tokyo.jp",".torun.pl",".tottori.jp",".toyama.jp",".tr",".trd.br",".tt",".tur.br",".tv",".tv.br",".tw",".tz",".ua",".ug",".uk",".us",".uy",".uz",".vc",".ve",".vet.br",".vg",".vgs.no",".vi",".vic.edu.au",".vic.gov.au",".vn",".volyn.ua",".vu",".wa.edu.au",".wa.gov.au",".wakayama.jp",".warszawa.pl",".waw.pl",".web.do",".web.id",".web.tr",".web.za",".wf",".wroc.pl",".ws",".yamagata.jp",".yamaguchi.jp",".yamanashi.jp",".ye",".yk.ca",".yokohama.jp",".yt",".za",".zgora.pl",".zm",".zw",".xn--80ao21a",".xn--j1amh",".xn--90ais",".xn--h2brj9c",".xn--fiqs8s",".xn--j6w193g"];

		if (substr($url, 0, 4) !== "http") {
			$url = 'https://' . $url;
		}

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return 'DOMAIN NOT FOUND';
		}

		$host = parse_url($url, PHP_URL_HOST);
		$cnt = substr_count($host, ".");
		while($cnt > 0) {
			$dmn = substr($host, strpos($host, "."));
			if (in_array($dmn, $tlds)) {
				return $host;
			}
			$cnt -= 1;
			$host = substr($dmn, 1);
		}
		return 'DOMAIN NOT FOUND';
	}

	/**
	 * Get domain extension from a URL/domain.
	 *
	 * @param array $params parameters of getDomainExtension details
	 *
	 * @return object result in text
	 */
	public function getDomainExtension($str)
	{
		$domain = $this->getDomainName($str);
		return substr($domain, strpos($domain, "."));
	}
}

class_alias('IP2LocationIO\DomainWhois', 'IP2LocationIO_DomainWhois');
