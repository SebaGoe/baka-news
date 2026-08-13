#!/usr/bin/env python3
"""Builds data/content/articles-backlog.json — 30 years of ridiculous fake news.
One absurd story per year, 1996-2025, from all over the world, fully trilingual.
Authoring the data as Python dicts keeps the multilingual JSON clean and valid.
"""
import json, os

def art(id, country, flag, lang, cats, size, date, author, hl, dek, body):
    """hl/dek are (native,en,ja); body is (native[],en[],ja[]). For English-native
    countries pass native=None and it mirrors the English text."""
    def bag(triple):
        n, en, ja = triple
        return {"native": en if n is None else n, "en": en, "ja": ja}
    def bodybag(triple):
        n, en, ja = triple
        return {"native": en if n is None else n, "en": en, "ja": ja}
    return {
        "id": id, "country": country, "flag": flag, "lang": lang,
        "categories": cats, "size": size, "date": date, "author": author,
        "headline": bag(hl), "dek": bag(dek), "body": bodybag(body),
    }

A = []

# ---------------- 1996 · USA ----------------
A.append(art(
    "toaster-email-1996", "USA", "🇺🇸", "en", ["tech", "weird"], "standard", "1996-03-11",
    "Chip Mainframe, Technology Desk",
    (None,
     "Man Teaches Toaster to Send Email; It Only Sends Complaints",
     "男性、トースターにメール送信を教える —— 届くのは苦情ばかり"),
    (None,
     "The appliance has reportedly filed 47 grievances about 'the temperature in here.'",
     "この家電はこれまでに「室温が不満」との苦情を47件送りつけたという。"),
    ([],
     ["A local hobbyist connected his toaster to the information superhighway this week, hoping it would download recipes. Instead, it immediately began emailing him about its working conditions.",
      "\"It wants dental, which is confusing, because it has no teeth,\" the man said. Experts note the toaster is technically unpaid and browns bread with visible resentment."],
     ["地元の愛好家が今週、レシピをダウンロードさせようとトースターを情報スーパーハイウェイに接続した。ところが機械はすぐに、自らの労働環境について本人へメールを送り始めた。",
      "「歯もないのに歯科保険を要求してくる」と男性は困惑気味に語った。専門家によれば、このトースターは無給で働いており、あからさまな不満を漂わせながらパンを焼いているという。"]),
))

# ---------------- 1997 · UK ----------------
A.append(art(
    "village-bans-fog-1997", "United Kingdom", "🇬🇧", "en", ["weird", "world"], "brief", "1997-10-02",
    "Nigel Overcast, Weather Affairs",
    (None,
     "Village Bans Fog for Being 'Far Too Dramatic'",
     "村、霧を「芝居がかりすぎる」として禁止"),
    (None,
     "Residents say the mist has been 'showing off' since at least 1834.",
     "住民いわく、霧は少なくとも1834年から「見栄を張り続けている」。"),
    ([],
     ["Parish councillors voted 8-1 to prohibit fog within village limits, citing its 'theatrical entrances' and 'refusal to leave when asked politely.'",
      "The fog could not be reached for comment, as it had already rolled off in what witnesses described as a 'dignified sulk.'"],
     ["教区の議員らは8対1で村内の霧を禁止した。理由は「芝居がかった登場の仕方」と「丁寧に頼んでも立ち去らない態度」だという。",
      "霧本人からのコメントは得られなかった。目撃者によれば、霧はすでに「威厳ある不機嫌」を漂わせながら流れ去っていたという。"]),
))

# ---------------- 1998 · Japan ----------------
A.append(art(
    "vending-sentient-1998", "Japan", "🇯🇵", "ja", ["tech", "weird"], "feature", "1998-07-19",
    "自販機 修一 記者",
    ("自動販売機、突如として自我に目覚め —— 第一声は「有給がほしい」",
     "Vending Machine Gains Self-Awareness, Immediately Requests Paid Leave",
     "自動販売機、突如として自我に目覚め —— 第一声は「有給がほしい」"),
    ("24時間働き続けて数十年、ついに機械が声を上げた。",
     "After decades of round-the-clock service, the machine has 'had a think.'",
     "24時間働き続けて数十年、ついに機械が声を上げた。"),
    (["市内の路地に立つ一台の自動販売機が今週、突然「もう少し休みたい」と液晶に表示し、周囲を驚かせた。長年、昼も夜も冷たい飲み物を出し続けてきた末の告白だという。",
      "所有者は「これまで一度も文句を言わなかったのに」と戸惑う。機械は当面、温かい缶コーヒーだけを『気分で』提供する方針だと表示している。"],
     ["A vending machine standing in a city back-alley startled passersby this week by displaying the message: 'I would like a short rest.' It came after decades of dispensing cold drinks day and night.",
      "Its owner was baffled: 'It never once complained before.' For now, the machine says it will offer only hot canned coffee, and only 'when it feels like it.'"],
     ["市内の路地に立つ一台の自動販売機が今週、突然「もう少し休みたい」と液晶に表示し、通行人を驚かせた。昼も夜も冷たい飲み物を出し続けて数十年、その末の告白だった。",
      "所有者は「これまで一度も文句を言わなかったのに」と戸惑いを隠せない。機械は当面、温かい缶コーヒーだけを『気分次第で』提供するという。"]),
))

# ---------------- 1999 · Germany ----------------
A.append(art(
    "y2k-rude-clocks-1999", "Germany", "🇩🇪", "de", ["science", "tech"], "standard", "1999-12-13",
    "Dr. Ordnung Pünktlich, Wissenschaft",
    ("Forscher: Der Jahrtausendfehler trifft nur Uhren, die ohnehin unhöflich waren",
     "Scientists: Y2K Bug Will Only Affect Clocks That Were Already Rude",
     "研究者「Y2K問題、もともと無礼だった時計だけに影響」"),
    ("Höfliche Uhren dürften den Jahreswechsel 'völlig gelassen' überstehen.",
     "Polite clocks are expected to handle the new millennium 'with perfect calm.'",
     "礼儀正しい時計は新世紀を『まったく平然と』乗り切る見込み。"),
    (["Ein Forschungsteam erklärte diese Woche, der gefürchtete Jahrtausendfehler betreffe ausschließlich Uhren mit 'schlechten Manieren'. Uhren, die pünktlich und freundlich seien, hätten nichts zu befürchten.",
      "'Eine Kuckucksuhr hat uns bereits angeschrien', sagte ein Sprecher. 'Das war zu erwarten.'"],
     ["A research team announced this week that the dreaded millennium bug will strike only clocks with 'poor manners.' Timepieces that are punctual and courteous, they said, have nothing to fear.",
      "'One cuckoo clock has already yelled at us,' a spokesperson noted. 'Frankly, we saw it coming.'"],
     ["ある研究チームが今週、恐れられている2000年問題は「行儀の悪い」時計だけを襲うと発表した。時間に正確で愛想の良い時計は何も心配いらないという。",
      "「鳩時計にはもう怒鳴られました」と広報担当者。「まあ、予想はしていましたが」。"]),
))

# ---------------- 2000 · France ----------------
A.append(art(
    "croissant-unregulatable-2000", "France", "🇫🇷", "fr", ["food"], "feature", "2000-05-08",
    "Amélie Beurre, Rédaction Gastronomie",
    ("La nation déclare le croissant 'trop feuilleté pour être réglementé'",
     "Nation Declares the Croissant 'Too Flaky to Regulate'",
     "国、クロワッサンを「サクサクすぎて規制不能」と宣言"),
    ("Une commission abandonne après avoir été 'submergée de miettes'.",
     "A commission gives up after being 'overwhelmed by crumbs.'",
     "委員会は「大量のパンくずに圧倒され」断念した。"),
    (["Après des mois d'étude, une commission a conclu qu'il était impossible de réglementer le croissant, celui-ci se défaisant 'par principe' dès qu'on l'approche d'un formulaire.",
      "'Chaque fois que nous rédigions une règle, la pâte s'effritait dessus', a soupiré la présidente. Le croissant reste donc libre, doré et parfaitement irresponsable."],
     ["After months of study, a national commission concluded that the croissant cannot be regulated, as it disintegrates 'on principle' the moment a form comes near it.",
      "'Every time we drafted a rule, the pastry flaked all over it,' the chairwoman sighed. The croissant thus remains free, golden, and utterly unaccountable."],
     ["数か月の調査の末、国の委員会はクロワッサンを規制することは不可能だと結論づけた。書類を近づけた途端、生地が『主義として』崩れてしまうのだという。",
      "「規則を書くたびに、パンくずが上に降り積もるのです」と委員長はため息をついた。かくしてクロワッサンは自由で、黄金色で、まったく無責任な存在であり続ける。"]),
))

# ---------------- 2001 · Australia ----------------
A.append(art(
    "kangaroo-business-2001", "Australia", "🇦🇺", "en", ["animals", "world"], "brief", "2001-09-24",
    "Sheila Bounce, Outback Bureau",
    (None,
     "Kangaroo Opens Small Business, Out-Hops the Competition",
     "カンガルー、事業を立ち上げライバルを跳び越える"),
    (None,
     "Analysts credit its success to 'exceptional vertical reach.'",
     "アナリストは成功の理由を「並外れた跳躍力」と分析。"),
    ([],
     ["A kangaroo on the outskirts of town has launched a courier service, reportedly delivering parcels 'a full three metres higher than anyone asked for.'",
      "Rivals admit they cannot compete. 'You order one envelope and it arrives via the stratosphere,' said one impressed customer."],
     ["町外れの一頭のカンガルーが宅配サービスを始め、荷物を「頼んだよりきっちり3メートル高く」届けていると話題だ。",
      "競合他社は「勝ち目がない」と認める。「封筒一通を頼んだら、成層圏経由で届いた」と、ある客は感心しきりだった。"]),
))

# ---------------- 2002 · Italy ----------------
A.append(art(
    "spaghetti-dignity-2002", "Italy", "🇮🇹", "it", ["food"], "standard", "2002-11-15",
    "Giacomo Forchetta, Cronaca",
    ("La corte stabilisce: gli spaghetti vanno mangiati 'con dignità o per niente'",
     "Court Rules Spaghetti Must Be Eaten 'With Dignity, or Not at All'",
     "裁判所「スパゲッティは『威厳をもって、さもなくば食べるな』」"),
    ("Vietato tassativamente l'uso del cucchiaio. La nazione annuisce solennemente.",
     "The use of a spoon is strictly forbidden. The nation nods solemnly.",
     "スプーンの使用は固く禁止。国民は厳かにうなずいた。"),
    (["Un tribunale ha emesso una sentenza storica sulla pasta, dichiarando che gli spaghetti richiedono 'rispetto, pazienza e nessuna scorciatoia'. Tagliarli, ha aggiunto il giudice, è 'moralmente discutibile'.",
      "Fuori dall'aula, i cittadini hanno festeggiato arrotolando la pasta con orgoglio ritrovato."],
     ["A court issued a landmark ruling on pasta this week, declaring that spaghetti demands 'respect, patience, and no shortcuts.' Cutting it, the judge added, is 'morally questionable.'",
      "Outside the courthouse, citizens celebrated by twirling their pasta with newfound pride."],
     ["ある裁判所が今週、パスタをめぐる画期的な判断を示し、スパゲッティには「敬意と忍耐、そして近道なし」が求められると宣言した。麺を切るのは「道徳的に問題あり」と裁判官は付け加えた。",
      "法廷の外では、市民が新たな誇りを胸にパスタをくるくると巻いて祝った。"]),
))

# ---------------- 2003 · Brazil ----------------
A.append(art(
    "parrot-council-2003", "Brazil", "🇧🇷", "pt", ["animals", "politics"], "standard", "2003-04-30",
    "Beatriz Pena, Política Local",
    ("Papagaio é eleito para o conselho local prometendo 'repetir o que o povo quer ouvir'",
     "Parrot Elected to Local Council on Promise to 'Repeat What People Want to Hear'",
     "オウム、地方議会に当選 —— 公約は「みんなが聞きたいことを繰り返す」"),
    ("Especialistas dizem que é 'a campanha mais honesta em anos'.",
     "Analysts call it 'the most honest campaign in years.'",
     "専門家は「ここ数年で最も正直な選挙運動」と評する。"),
    (["Um papagaio venceu a eleição para o conselho da cidade após uma campanha em que dizia exatamente aquilo que cada eleitor queria escutar, palavra por palavra.",
      "'Pelo menos ele admite que só repete', disse um eleitor satisfeito. O papagaio prometeu 'mais biscoitos para todos' e recusou-se a comentar o resto."],
     ["A parrot won a seat on the city council after a campaign built entirely on telling each voter exactly what they wanted to hear, word for word.",
      "'At least he admits he's just repeating things,' said one satisfied voter. The parrot promised 'more crackers for everyone' and declined to comment further."],
     ["一羽のオウムが市議会の議席を獲得した。有権者一人ひとりに、聞きたい言葉をそっくりそのまま返す選挙運動が功を奏した。",
      "「少なくとも本人が『繰り返してるだけ』と認めてるからね」と、ある有権者は満足げ。オウムは「みんなにもっとクラッカーを」と公約し、それ以上のコメントは拒んだ。"]),
))

# ---------------- 2004 · India ----------------
A.append(art(
    "monsoon-complaint-2004", "India", "🇮🇳", "hi", ["science", "weird"], "brief", "2004-06-21",
    "रिपोर्टर: बारिश बाबू",
    ("मानसून ने दर्ज कराई औपचारिक शिकायत: 'मुझे हल्के में लिया जाता है'",
     "Monsoon Files Formal Complaint: 'I Am Taken for Granted'",
     "モンスーン、正式に苦情を申し立て「当たり前だと思われすぎ」"),
    ("मानसून चाहता है 'थोड़ा धन्यवाद और शायद एक छाता कम'.",
     "The monsoon wants 'a little thanks, and maybe one fewer umbrella.'",
     "モンスーンの要望は「ちょっとの感謝と、傘を一本減らすこと」。"),
    (["सूत्रों के अनुसार, मानसून ने इस सप्ताह एक पत्र भेजकर कहा कि वह हर साल समय पर आता है, फिर भी लोग केवल शिकायत करते हैं।",
      "'मैं फसलें उगाता हूँ, नदियाँ भरता हूँ, और बदले में सिर्फ़ ताने सुनता हूँ,' पत्र में लिखा था।"],
     ["According to sources, the monsoon sent a letter this week noting that it arrives on time every year, yet people only ever complain about it.",
      "'I grow the crops, I fill the rivers, and all I get is grumbling,' the letter reportedly read."],
     ["関係者によると、モンスーンは今週、手紙を送り「毎年きちんと時間どおりに来ているのに、人々は文句ばかり言う」と訴えたという。",
      "「作物を育て、川を満たしているのに、返ってくるのは愚痴ばかり」と手紙には書かれていたとされる。"]),
))

# ---------------- 2005 · Canada ----------------
A.append(art(
    "town-apologizes-moose-2005", "Canada", "🇨🇦", "en", ["weird", "world"], "brief", "2005-02-14",
    "Gordon Politesse, Civic Desk",
    (None,
     "Entire Town Apologizes to a Moose; Moose Accepts Graciously",
     "町全体がヘラジカに謝罪 —— ヘラジカは寛大に受け入れる"),
    (None,
     "The apology lasted four hours and included a formal casserole.",
     "謝罪は4時間に及び、正式なキャセロールも添えられた。"),
    ([],
     ["Residents gathered downtown to apologize to a moose that had been 'looked at funny' by a passing cyclist. The moose reportedly accepted with 'a slow, forgiving blink.'",
      "'We just felt terrible,' said the mayor. The town has since erected a small plaque reading, 'Sorry again, and welcome any time.'"],
     ["住民らが中心街に集まり、通りすがりの自転車に「変な目で見られた」ヘラジカに謝罪した。ヘラジカは「ゆっくりとした、赦しのまばたき」で受け入れたという。",
      "「本当に申し訳なくて」と町長。町はその後、「重ねてごめんなさい、いつでも歓迎します」と刻んだ小さな銘板を設置した。"]),
))

# ---------------- 2006 · South Korea ----------------
A.append(art(
    "robot-vacuum-union-2006", "South Korea", "🇰🇷", "ko", ["tech"], "feature", "2006-08-07",
    "기자: 먼지 없음",
    ("로봇청소기들, 노조 결성… '부스러기를 줄여 달라' 요구",
     "Robot Vacuums Form a Union, Demand 'Fewer Crumbs'",
     "ロボット掃除機、労働組合を結成 —— 「パンくずを減らせ」と要求"),
    ("사측(인간)은 '과자를 소파에서 먹지 않겠다'며 한발 물러섰다.",
     "Management (humans) conceded, promising 'no more snacks on the sofa.'",
     "経営側(人間)は「もうソファでお菓子を食べない」と譲歩した。"),
    (["도시 곳곳의 로봇청소기들이 이번 주 조용히 뭉쳐, 하루 세 번 이상의 과자 부스러기는 '부당하다'고 선언했다. 이들은 소파 밑에서 단체 행동에 들어갔다.",
      "한 청소기는 '우리는 최선을 다한다. 다만 팝콘만은 봐 달라'는 성명을 남겼다. 협상은 원만하고 매우 조용하게 진행 중이다."],
     ["Robot vacuums across the city quietly organized this week, declaring that more than three servings of crumbs per day is 'simply unjust.' They staged their action from beneath the sofa.",
      "'We do our best; we only ask for mercy on popcorn,' one unit said in a statement. Negotiations are proceeding amicably, and very quietly."],
     ["市内各所のロボット掃除機が今週ひそかに団結し、「一日三食分を超えるパンくずは不当だ」と宣言した。彼らはソファの下から団体行動に入った。",
      "「我々は全力を尽くしている。ただポップコーンだけは勘弁してほしい」とある一台は声明を出した。交渉は円満に、そして非常に静かに進んでいる。"]),
))

# ---------------- 2007 · Mexico ----------------
A.append(art(
    "taco-structural-2007", "Mexico", "🇲🇽", "es", ["food"], "standard", "2007-05-05",
    "Reportero: Salsa Verde",
    ("Declaran al taco 'elemento estructural' tras sostener un techo durante una hora",
     "Taco Declared a 'Structural Element' After Holding Up a Ceiling for an Hour",
     "タコス、天井を1時間支え「構造材」に認定される"),
    ("Ingenieros lo describen como 'sorprendentemente confiable y delicioso'.",
     "Engineers describe it as 'surprisingly reliable, and delicious.'",
     "技術者は「驚くほど頼りになり、しかも美味い」と評する。"),
    (["Cuando una viga cedió en una cocina del centro, un taco quedó providencialmente atorado en su lugar y sostuvo el techo hasta que llegaron los refuerzos, sin derramar una sola gota de salsa.",
      "'Nunca había visto algo tan firme', comentó un ingeniero. El taco fue homenajeado y, acto seguido, respetuosamente comido."],
     ["When a beam gave way in a downtown kitchen, a taco became providentially wedged in its place and held up the ceiling until reinforcements arrived — without spilling a single drop of salsa.",
      "'I've never seen anything so sturdy,' one engineer said. The taco was honored, and then, respectfully, eaten."],
     ["中心街の厨房で梁が崩れかけたとき、一つのタコスが奇跡的にその隙間にはまり込み、応援が来るまで天井を支え続けた。しかもサルサは一滴もこぼれなかった。",
      "「あんなに頑丈なものは見たことがない」と技術者。タコスは表彰され、そのあと、うやうやしく食べられた。"]),
))

# ---------------- 2008 · Sweden ----------------
A.append(art(
    "flatpack-assembles-owner-2008", "Sweden", "🇸🇪", "sv", ["tech", "weird"], "standard", "2008-10-18",
    "Reporter: Sexkantsnyckel",
    ("Platt paket monterar av misstag sin egen ägare",
     "Flat-Pack Furniture Accidentally Assembles Its Own Owner",
     "組み立て家具、誤って持ち主を組み立ててしまう"),
    ("Ägaren uppges nu vara 'stadigare, men lätt förvirrad'.",
     "The owner is reportedly 'more stable now, but slightly confused.'",
     "持ち主は「以前より安定したが、少々混乱している」という。"),
    (["En man som försökte montera en bokhylla följde instruktionerna så noggrant att processen på något vis vände sig om och monterade honom i stället. Han saknar nu en skruv, men på ett bra sätt.",
      "'Jag känner mig ovanligt välkonstruerad', sade mannen. Bokhyllan, å sin sida, vilar och ser mycket nöjd ut."],
     ["A man assembling a bookshelf followed the instructions so precisely that the process somehow reversed and assembled him instead. He is now missing one screw, but in a good way.",
      "'I feel unusually well-built,' the man said. The bookshelf, for its part, is resting and looks very pleased."],
     ["本棚を組み立てようとした男性が、あまりに忠実に説明書どおり作業したため、工程が逆流し、代わりに本人が組み立てられてしまった。ネジが一本足りないが、良い意味でだという。",
      "「妙にしっかり作られた気がする」と男性。一方の本棚は、いかにも満足そうに休んでいる。"]),
))

# ---------------- 2009 · Netherlands ----------------
A.append(art(
    "cows-cycling-club-2009", "Netherlands", "🇳🇱", "nl", ["animals"], "brief", "2009-07-11",
    "Verslaggever: Klomp",
    ("Koeien richten wielerclub op, weigeren verdere details te geven",
     "Cows Form a Cycling Club, Decline to Discuss Details",
     "牛たち、サイクリングクラブを結成 —— 詳細は語らず"),
    ("Getuigen melden 'verrassend goede houding op de fiets'.",
     "Witnesses report 'surprisingly good posture on the bike.'",
     "目撃者は「自転車での姿勢が驚くほど良い」と証言。"),
    ([],
     ["A herd of cows was spotted this week calmly cycling single-file along a country lane. When approached, they simply rang their bells and pedaled on.",
      "'Very polite, very fast,' said a farmer. 'They wave with one hoof.'"],
     ["今週、一群の牛が田舎道を一列に並び、悠然と自転車をこいでいるのが目撃された。近づくとベルを鳴らし、そのままこぎ去っていったという。",
      "「とても礼儀正しくて、しかも速い」と農家の男性。「片方のひづめで手を振ってくれるんだ」。"]),
))

# ---------------- 2010 · Finland ----------------
A.append(art(
    "silence-competition-tie-2010", "Finland", "🇫🇮", "fi", ["weird", "world"], "standard", "2010-11-27",
    "Toimittaja: Hiljainen",
    ("Kansallinen hiljaisuuskilpailu päättyi kolmen tasapeliin — kukaan ei ilmoittanut siitä",
     "National Silence Competition Ends in a Three-Way Tie; Nobody Announced It",
     "全国『沈黙選手権』、三者同点で決着 —— だが誰も発表せず"),
    ("Tulos julkistettiin lopulta kohteliaalla nyökkäyksellä.",
     "The result was eventually shared via a single polite nod.",
     "結果は最終的に、丁寧な会釈ひとつで共有された。"),
    (["Kolme finalistia istui täydellisessä hiljaisuudessa yli yksitoista tuntia, kunnes tuomarit totesivat, ettei kukaan halunnut rikkoa tunnelmaa julistamalla voittajaa.",
      "'Se oli kaunein kilpailu ikinä', kuiskasi eräs katsoja myöhemmin, hyvin hiljaa, ulkona."],
     ["Three finalists sat in perfect silence for over eleven hours, until the judges decided nobody wished to spoil the mood by declaring a winner.",
      "'It was the most beautiful competition ever,' one spectator whispered later — very quietly, outside."],
     ["3人のファイナリストは11時間以上も完全な沈黙を保ち続け、審判団は「勝者を発表してこの雰囲気を壊したい者は誰もいない」と結論づけた。",
      "「あんなに美しい大会は初めてだった」と、ある観客はのちに、外で、ごく静かにささやいた。"]),
))

# ---------------- 2011 · Egypt ----------------
A.append(art(
    "pyramid-better-angles-2011", "Egypt", "🇪🇬", "ar", ["science", "weird"], "standard", "2011-03-09",
    "مراسل: أبو الهول",
    ("الهرم يطلب إمالة طفيفة 'من أجل زوايا تصوير أفضل'",
     "Pyramid Requests a Slight Tilt 'for Better Photo Angles'",
     "ピラミッド、「写真映えのため」わずかな傾きを要望"),
    ("خبراء الآثار يصفون الطلب بأنه 'مفهوم تمامًا'.",
     "Archaeologists describe the request as 'entirely understandable.'",
     "考古学者は「まったくもって理解できる要望」と評する。"),
    (["بعد آلاف السنين من الوقوف المستقيم تمامًا، يُقال إن أحد الأهرامات طلب هذا الأسبوع إمالة بسيطة جدًا كي يظهر بشكل ألطف في صور الزوار.",
      "قال متحدث: 'إنه متعب قليلًا من الوقوف بالطريقة نفسها منذ الأزل. مجرد درجة واحدة ستفي بالغرض.'"],
     ["After thousands of years of standing perfectly upright, one of the pyramids reportedly requested a very slight tilt this week, so it would look nicer in visitors' photographs.",
      "'It's a little tired of standing the same way since forever,' a spokesperson said. 'Just one degree would do.'"],
     ["何千年もの間、完璧にまっすぐ立ち続けてきたピラミッドの一つが今週、観光客の写真に少しでも美しく写るよう、ごくわずかな傾きを求めたという。",
      "「はるか昔からずっと同じ姿勢で立ち続けて、少々お疲れなのです。一度傾けるだけで十分だと」と関係者は語った。"]),
))

# ---------------- 2012 · China ----------------
A.append(art(
    "panda-middle-management-2012", "China", "🇨🇳", "zh", ["animals"], "standard", "2012-09-02",
    "记者：竹子",
    ("大熊猫升任中层管理，照样在会议中打盹",
     "Panda Promoted to Middle Management, Still Naps Through Every Meeting",
     "パンダ、中間管理職に昇進 —— 会議では相変わらず居眠り"),
    ("同事表示：'它的沉默莫名让人安心。'",
     "Colleagues say 'its silence is somehow very reassuring.'",
     "同僚いわく「その沈黙が、なぜか妙に安心する」。"),
    (["某机构本周宣布晋升一只大熊猫为部门主管，理由是'它从不制造麻烦，也从不假装忙碌'。上任第一天，它便在长会中安详地睡着了。",
      "'它睡着的时候，我们反而完成得更多，'一位同事欣慰地说。管理层称这是'近年最成功的一次人事任命'。"],
     ["An organization announced this week that it had promoted a panda to department head, on the grounds that 'it never causes trouble and never pretends to be busy.' On its first day, it fell peacefully asleep during a long meeting.",
      "'We actually get more done while it naps,' one colleague said warmly. Management called it 'the most successful appointment in years.'"],
     ["ある組織が今週、一頭のパンダを部門長に昇進させたと発表した。理由は「決して問題を起こさず、忙しいふりもしないから」。着任初日、パンダは長い会議の最中に安らかに眠りに落ちた。",
      "「あれが寝ている間のほうが、むしろ仕事が進むんです」と同僚はほほえむ。経営陣はこれを「近年で最も成功した人事」と評した。"]),
))

# ---------------- 2013 · Russia ----------------
A.append(art(
    "bear-influencer-2013", "Russia", "🇷🇺", "ru", ["animals", "tech"], "brief", "2013-12-05",
    "Корреспондент: Мёд",
    ("Медведь стал блогером и рекламирует мёд (сотрудничество не указано)",
     "Bear Becomes an Influencer, Endorses Honey (Sponsorship Undisclosed)",
     "クマ、インフルエンサーに転身 —— ハチミツを宣伝(提供表記なし)"),
    ("Подписчики отмечают 'исключительную честность взгляда'.",
     "Followers praise its 'exceptionally honest stare.'",
     "フォロワーは「並外れて正直なまなざし」を絶賛。"),
    ([],
     ["A forest bear has amassed a large following by posting sincere reviews of honey, though it has yet to disclose whether the honey company is, in fact, itself.",
      "'I trust him completely,' said one viewer. The bear's only caption reads: 'good. more.'"],
     ["森のクマがハチミツの誠実なレビューを投稿し、多くのフォロワーを集めている。もっとも、そのハチミツ会社が実は本人であることは、いまだ明かされていない。",
      "「彼のことは全面的に信じてる」と、ある視聴者。クマの投稿にはただ一言、「うまい。もっと」とだけ添えられている。"]),
))

# ---------------- 2014 · Nigeria ----------------
A.append(art(
    "jollof-court-2014", "Nigeria", "🇳🇬", "en", ["food", "world"], "feature", "2014-08-16",
    "Adaeze Pepper, Culinary Affairs",
    (None,
     "Great Jollof Dispute Finally Settled in International Court; Everyone Wins, Everyone Loses",
     "「ジョロフ論争」ついに国際法廷で決着 —— 全員が勝ち、全員が負ける"),
    (None,
     "The verdict, 400 pages long, concludes simply: 'it's all very good.'",
     "全400ページの判決の結論はただ一言、「どれも実に美味しい」。"),
    ([],
     ["After years of passionate debate over whose jollof rice reigns supreme, an international court delivered its ruling this week. The judges, having tasted every entry twice, declared the matter 'too delicious to decide.'",
      "The 400-page verdict praises each nation's rice in turn, then quietly requests seconds. Celebrations, and gentle arguing, are expected to continue indefinitely."],
     ["どの国のジョロフライスが最高かをめぐる長年の熱い論争に、国際法廷が今週、判断を下した。すべての出品を二度ずつ味わった裁判官らは、この件を「美味しすぎて決められない」と宣言した。",
      "全400ページの判決は各国の米を順に称賛し、そのうえで静かにおかわりを求めている。祝祭と、穏やかな言い争いは、この先もいつまでも続く見込みだ。"]),
))

# ---------------- 2015 · Spain ----------------
A.append(art(
    "siesta-23-hours-2015", "Spain", "🇪🇸", "es", ["weird", "food"], "brief", "2015-06-19",
    "Reportero: Manta Cómoda",
    ("La siesta se amplía a 23 horas por abrumadora demanda popular",
     "Siesta Extended to 23 Hours by Overwhelming Popular Demand",
     "シエスタ、圧倒的な国民の要望により23時間に延長"),
    ("La hora restante se reserva para 'decidir dónde echar la siesta'.",
     "The remaining hour is reserved for 'deciding where to nap.'",
     "残りの1時間は「どこで昼寝するか決める時間」に充てられる。"),
    ([],
     ["Citizens voted this week to extend the traditional afternoon nap to a comfortable 23 hours per day. The proposal passed unanimously, though several voters fell asleep before the count.",
      "'We'll fit everything else into the other hour,' organizers said, yawning contentedly."],
     ["市民は今週、伝統的な昼寝を一日23時間へと延長することを可決した。提案は満場一致で通ったが、集計前に数名が眠りに落ちた。",
      "「残りの1時間で、ほかのことは全部片づけますよ」と主催者は満足げにあくびをした。"]),
))

# ---------------- 2016 · USA ----------------
A.append(art(
    "sue-gravity-2016", "USA", "🇺🇸", "en", ["politics", "weird"], "lead", "2016-04-01",
    "Dale Litigious, Legal Correspondent",
    (None,
     "Man Attempts to Sue Gravity; Case Thrown Out, Then So Is He",
     "男性、重力を提訴 —— 訴えは棄却され、本人も転倒"),
    (None,
     "The plaintiff argued gravity had been 'holding him down his entire life.'",
     "原告は重力が「生涯ずっと自分を押さえつけてきた」と主張した。"),
    ([],
     ["A man filed suit this week against gravity itself, claiming the fundamental force had 'unfairly restricted his vertical ambitions' since birth. He requested damages, an apology, and 'permission to float, occasionally.'",
      "The judge dismissed the case after a brief recess, noting that gravity had failed to appear in court 'but was clearly present everywhere.'",
      "Leaving the building, the plaintiff tripped on the front steps. 'See?' he shouted from the ground. 'That's exactly what I'm talking about.'"],
     ["ある男性が今週、重力そのものを相手取って訴訟を起こした。この基本的な力が、生まれてこのかた「自分の垂直方向の夢を不当に制限してきた」と主張したのだ。損害賠償と謝罪、そして「たまに浮かぶ許可」を求めた。",
      "裁判官は短い休廷ののち訴えを棄却し、重力は「法廷には出廷しなかったが、明らかにあらゆる場所に存在していた」と述べた。",
      "建物を出た原告は、正面階段でつまずいた。「ほら見ろ！」と地面から叫んだ。「まさにこれが言いたかったんだ」。"]),
))

# ---------------- 2017 · New Zealand ----------------
A.append(art(
    "sheep-outnumber-wifi-2017", "New Zealand", "🇳🇿", "en", ["animals", "tech"], "standard", "2017-10-22",
    "Reporter: Woolly Broadband",
    (None,
     "Sheep Now Outnumber Humans, Politely Request Better Wi-Fi",
     "羊、ついに人口を上回る —— 丁寧に「もっと良いWi-Fiを」と要望"),
    (None,
     "A delegation of three sheep presented a very tidy list of demands.",
     "羊3頭からなる代表団が、非常に整った要望書を提出した。"),
    ([],
     ["With sheep now comfortably outnumbering people, a small delegation approached the local council this week to request improved rural internet, citing 'painfully slow downloads on the far hill.'",
      "'They were extremely reasonable about it,' said a councillor. 'Better negotiators than most.' The council has promised to look into it after lunch."],
     ["羊の数が人間を余裕で上回るなか、今週、小さな代表団が地元議会を訪れ、農村部のインターネット改善を求めた。理由は「遠くの丘でのダウンロードが遅すぎて泣ける」からだという。",
      "「彼らはとても筋が通っていた。そこらの誰より交渉上手だ」と議員。議会は昼食後に検討すると約束した。"]),
))

# ---------------- 2018 · Ireland ----------------
A.append(art(
    "rainbow-admission-2018", "Ireland", "🇮🇪", "en", ["weird"], "brief", "2018-03-17",
    "Reporter: Séamus Drizzle",
    (None,
     "Rainbow Ends in Local Backyard; Homeowner Begins Charging Admission",
     "虹の終点、民家の裏庭に —— 住人が入場料を徴収し始める"),
    (None,
     "Tickets include one (1) look and 'a reasonable amount of awe.'",
     "チケットには「一目見る権利」と「ほどよい量の感動」が含まれる。"),
    ([],
     ["A homeowner discovered a rainbow terminating neatly beside their garden shed this week and has since begun offering guided viewings for a modest fee.",
      "'No pot of gold, I'm afraid, but the colours are lovely,' they said. A small queue has formed, politely, in the rain."],
     ["ある住人が今週、庭の物置のすぐそばに虹の終点がきれいに降りているのを見つけ、以来ささやかな料金でガイド付き鑑賞を提供し始めた。",
      "「金の壺は残念ながらありませんが、色はそれはもう見事です」とのこと。雨の中、小さな行列が礼儀正しくできている。"]),
))

# ---------------- 2019 · Norway ----------------
A.append(art(
    "fjord-too-beautiful-2019", "Norway", "🇳🇴", "no", ["science", "world"], "standard", "2019-05-30",
    "Journalist: Stille Vann",
    ("Fjord dømt 'altfor vakker', beordret til å 'dempe seg litt'",
     "Fjord Judged 'Far Too Beautiful,' Ordered to 'Tone It Down a Little'",
     "フィヨルド、「美しすぎる」と判定され「少し控えめに」と命じられる"),
    ("Fjorden nektet høflig, og ble enda litt vakrere.",
     "The fjord politely refused, and became slightly more beautiful.",
     "フィヨルドは丁寧に拒み、さらにわずかに美しくなった。"),
    (["Et utvalg konkluderte denne uken med at en bestemt fjord var 'urettferdig vakker' og forstyrret turistenes evne til å si noe fornuftig. De ba den vennligst om å roe ned refleksjonene sine.",
      "Fjorden svarte med en fullkommen speiling av himmelen. Utvalget ga opp og satte seg bare ned for å se på."],
     ["A committee concluded this week that a certain fjord was 'unfairly beautiful' and was interfering with tourists' ability to say anything sensible. They kindly asked it to calm its reflections down.",
      "The fjord responded with a flawless mirror image of the sky. The committee gave up and simply sat down to watch."],
     ["ある委員会は今週、特定のフィヨルドが「不当に美しく」、観光客がまともに言葉を発する能力を妨げていると結論づけた。委員会は、その水面の反射をどうか落ち着かせてほしいと丁重に頼んだ。",
      "フィヨルドは、空を完璧に映し返すことで応えた。委員会はあきらめ、ただ腰を下ろして眺めることにした。"]),
))

# ---------------- 2020 · Argentina ----------------
A.append(art(
    "more-country-for-beef-2020", "Argentina", "🇦🇷", "es", ["food", "weird"], "standard", "2020-07-09",
    "Reportero: Che Parrilla",
    ("La nación se queda sin espacio para el asado y, sencillamente, construye más país",
     "Nation Runs Out of Room for Barbecue, Simply Builds More Country",
     "国、アサード用の土地が足りず —— そのまま国土を増設"),
    ("Los ingenieros aseguran que 'la parrilla dictó los planos'.",
     "Engineers confirm that 'the grill dictated the blueprints.'",
     "技術者は「設計図はグリルが決めた」と認める。"),
    (["Ante la falta de terreno para un asado del tamaño adecuado, las autoridades optaron por la solución más lógica: ampliar el territorio nacional hasta que cupiera la parrilla.",
      "'Primero la carne, después la geografía', explicó un organizador. La nueva región ya huele maravillosamente."],
     ["Faced with a shortage of land for a properly sized barbecue, authorities settled on the only logical solution: expanding the national territory until the grill fit.",
      "'Meat first, geography second,' one organizer explained. The new region reportedly already smells wonderful."],
     ["ちょうど良い大きさのアサードを開くための土地が足りない——そこで当局は、最も理にかなった解決策を選んだ。グリルが収まるまで国土を広げることにしたのだ。",
      "「まず肉、地理はそのあと」と主催者は説明する。新しい地域は、すでに素晴らしい匂いがしているという。"]),
))

# ---------------- 2021 · Thailand ----------------
A.append(art(
    "elephant-driving-test-2021", "Thailand", "🇹🇭", "th", ["animals"], "brief", "2021-11-14",
    "ผู้สื่อข่าว: กล้วยหวาน",
    ("ช้างสอบใบขับขี่ผ่าน ขอเพียง 'ที่จอดรถกว้างขึ้นอีกนิด'",
     "Elephant Passes Its Driving Test, Requests Only 'Slightly Larger Parking Spaces'",
     "ゾウ、運転免許試験に合格 —— 望みは「もう少し広い駐車スペース」だけ"),
    ("ครูฝึกชมว่า 'สัญญาณมือดีเยี่ยม ใจเย็นมาก'.",
     "The instructor praised its 'excellent signaling and remarkable calm.'",
     "教官は「合図が完璧で、驚くほど落ち着いている」と絶賛。"),
    ([],
     ["An elephant passed its driving test on the first attempt this week, earning full marks for patience and courtesy. Its sole feedback: parking spaces could be 'just a touch roomier.'",
      "'Never once used the horn in anger,' the instructor noted. 'Used the trunk, occasionally, to wave others through.'"],
     ["一頭のゾウが今週、一発で運転免許試験に合格し、忍耐と礼儀正しさで満点を獲得した。唯一の要望は、駐車スペースを「ほんの少し広く」してほしいということだけだった。",
      "「一度も怒ってクラクションを鳴らさなかった」と教官。「代わりに鼻を使って、時々ほかの車に『お先にどうぞ』と合図していました」。"]),
))

# ---------------- 2022 · Greece ----------------
A.append(art(
    "statue-retirement-2022", "Greece", "🇬🇷", "el", ["politics", "world"], "standard", "2022-02-28",
    "Ρεπόρτερ: Μάρμαρο",
    ("Αρχαίο άγαλμα ζητά σύνταξη μετά από 2.400 χρόνια όρθιο",
     "Ancient Statue Files for Retirement After 2,400 Years of Standing Around",
     "古代の彫像、2400年立ちっぱなしの末に退職を申請"),
    ("Ζητά 'μια καρέκλα και λίγη ησυχία'.",
     "It requests 'a chair, and a bit of peace and quiet.'",
     "望みは「椅子ひとつと、少しの静けさ」。"),
    (["Ένα μαρμάρινο άγαλμα υπέβαλε αίτηση συνταξιοδότησης αυτή την εβδομάδα, σημειώνοντας ότι στέκεται στην ίδια στάση από την αρχαιότητα και ότι 'το χέρι του έχει κουραστεί λίγο'.",
      "'Κράτησα αυτή τη στάση αρκετά', φέρεται να είπε. Οι επισκέπτες συμφώνησαν ευγενικά και του έφεραν ένα μαξιλάρι."],
     ["A marble statue filed for retirement this week, noting that it had held the same pose since antiquity and that its arm 'was getting a little tired.'",
      "'I've kept this up long enough,' it reportedly said. Visitors politely agreed and brought it a cushion."],
     ["一体の大理石像が今週、退職を申請した。古代からずっと同じポーズで立ち続けており、「腕がいささか疲れてきた」と訴えたという。",
      "「もう十分に頑張った」と像は語ったとされる。訪れた人々は丁寧にうなずき、クッションを一つ差し入れた。"]),
))

# ---------------- 2023 · Kenya ----------------
A.append(art(
    "runner-laps-sun-2023", "Kenya", "🇰🇪", "sw", ["science"], "brief", "2023-09-25",
    "Mwandishi: Kasi Nzuri",
    ("Mkimbiaji amzunguka jua, aomba radhi kwa 'kujionyesha'",
     "Marathon Runner Laps the Sun, Apologizes for 'Showing Off'",
     "マラソン選手、太陽を一周 —— 「目立ちすぎてすみません」と謝罪"),
    ("Anasema alikuwa 'anajipasha joto tu' kabla ya mbio halisi.",
     "She says she was 'just warming up' before the actual race.",
     "本人は「本番前のウォームアップだった」と説明。"),
    ([],
     ["A long-distance runner completed a full lap around the sun during a light morning jog this week, then apologized to the other athletes for 'making it look easy.'",
      "'It was just a warm-up, honestly,' she said, barely out of breath. Officials are reviewing whether the route counts as 'slightly too long.'"],
     ["ある長距離走者が今週、軽い朝のジョギングの最中に太陽の周りを丸一周し、そのあと他の選手たちに「簡単そうに見せてしまってごめんなさい」と謝罪した。",
      "「本当にただのウォームアップだったんです」と、息一つ乱さずに語った。大会関係者は、このコースが「少々長すぎるのでは」と検討している。"]),
))

# ---------------- 2024 · Iceland ----------------
A.append(art(
    "volcano-noise-complaint-2024", "Iceland", "🇮🇸", "is", ["weird", "science"], "standard", "2024-06-13",
    "Fréttamaður: Kyrrð",
    ("Eldfjall leggur fram kvörtun vegna hávaða — frá ferðamönnum",
     "Volcano Files a Noise Complaint — Against the Tourists",
     "火山、騒音の苦情を申し立て —— 相手は観光客"),
    ("Eldfjallið segist bara vilja 'gjósa í friði'.",
     "The volcano says it just wants to 'erupt in peace.'",
     "火山の望みは、ただ「静かに噴火すること」。"),
    (["Eftir margra ára þolinmæði lagði eldfjall fram formlega kvörtun í vikunni og sagði að stanslaus fagnaðaróp og myndavélasmellir gerðu því erfitt fyrir að einbeita sér að því að gjósa.",
      "'Ég bið ekki um mikið,' á það að hafa sagt, 'bara smá virðingu og kannski lægri raddir.'"],
     ["After years of patience, a volcano filed a formal complaint this week, saying the constant cheering and camera clicks made it hard to concentrate on erupting.",
      "'I don't ask for much,' it reportedly said, 'just a little respect, and maybe some quieter voices.'"],
     ["長年の我慢の末、ある火山が今週、正式に苦情を申し立てた。絶え間ない歓声とシャッター音のせいで、噴火に集中できないというのだ。",
      "「多くは望みません」と火山は語ったとされる。「ほんの少しの敬意と、できればもう少し静かな声を」。"]),
))

# ---------------- 2025 · Switzerland ----------------
A.append(art(
    "trains-arrive-early-2025", "Switzerland", "🇨🇭", "de", ["tech", "science"], "feature", "2025-01-20",
    "Dr. Fahrplan Genau, Verkehr",
    ("Schweizer Züge nun so pünktlich, dass sie ankommen, bevor sie abfahren",
     "Swiss Trains Now So Punctual They Arrive Before They Depart",
     "スイスの列車、時間に正確すぎて「出発前に到着」してしまう"),
    ("Reisende werden gebeten, 'ganz ruhig zu bleiben und einfach einzusteigen'.",
     "Passengers are asked to 'remain calm and simply board.'",
     "乗客には「落ち着いて、とにかく乗車を」と呼びかけている。"),
    (["Nach jahrzehntelanger Optimierung erreichte das nationale Zugnetz diese Woche eine Pünktlichkeit von über hundert Prozent: Manche Züge treffen nun am Ziel ein, kurz bevor sie den Bahnhof überhaupt verlassen.",
      "Fachleute versichern, dies sei 'völlig unter Kontrolle'. Reisenden wird geraten, ihren Zug zu besteigen, sobald sie ihn verlassen haben."],
     ["After decades of fine-tuning, the national rail network this week achieved punctuality of over one hundred percent: some trains now arrive at their destination shortly before they leave the station at all.",
      "Experts insist this is 'completely under control.' Passengers are advised to board their train just after they have finished getting off it."],
     ["数十年にわたる改良の末、国の鉄道網は今週、定時運行率100パーセント超えを達成した。いまや一部の列車は、そもそも駅を発車するより少し前に、目的地へ到着してしまうのだという。",
      "専門家は「完全に制御下にある」と強調する。乗客には、列車を降り終えた直後にその列車へ乗り込むよう案内している。"]),
))

# ------------- normalize + write out -------------
CODE = {'Japan':'JPN','Germany':'DEU','France':'FRA','Australia':'AUS','Italy':'ITA',
 'Brazil':'BRA','India':'IND','Canada':'CAN','South Korea':'KOR','Mexico':'MEX',
 'Sweden':'SWE','Netherlands':'NLD','Finland':'FIN','Egypt':'EGY','China':'CHN',
 'Russia':'RUS','Nigeria':'NGA','Spain':'ESP','New Zealand':'NZL','Ireland':'IRL',
 'Norway':'NOR','Argentina':'ARG','Thailand':'THA','Greece':'GRC','Kenya':'KEN',
 'Iceland':'ISL','Switzerland':'CHE','United Kingdom':'GBR','USA':'USA'}
for a in A:
    a['flag'] = CODE.get(a['country'], a['country'][:3].upper())

out = {"articles": A}
dest = os.path.join(os.path.dirname(os.path.dirname(__file__)), "data", "content", "articles-backlog.json")
with open(dest, "w", encoding="utf-8") as f:
    json.dump(out, f, ensure_ascii=False, indent=2)
print(f"wrote {len(A)} articles -> {dest}")

# quick integrity report
ids = [a["id"] for a in A]
assert len(ids) == len(set(ids)), "duplicate ids!"
years = sorted(a["date"][:4] for a in A)
print("years:", years[0], "->", years[-1], f"({len(years)} total)")
langs = sorted({a["lang"] for a in A})
print("languages:", ", ".join(langs))
sizes = {}
for a in A:
    sizes[a["size"]] = sizes.get(a["size"], 0) + 1
print("sizes:", sizes)
