<?php

namespace App\Http\Controllers;

use Auth;
use App;
use Artisan;
use Config;
use DB;
use Validator;
use App\User;
use App\Project;
use Hash;
use Session;
use Redirect;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use DirectoryIterator;
use RecursiveDirectoryIterator;
use InvalidArgumentException;
use FilesystemIterator;
use ErrorException;
use RecursiveIteratorIterator;

use Illuminate\Http\Request;
use Illuminate\Console\Application;
use Symfony\Component\Console\Output\StreamOutput;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isVerified');
        $this->middleware('active');
        $this->middleware('isAdmin');
    }

    // Add project
    public function addProject(Request $request) {
        return view('dashboard.addProject', [
            'User' => Auth::user(),
            'activePage' => 'projects'
        ]);
    }

    // Create project
    public function createProject(Request $request) {
        $validation = Validator::make($request->all(), [
            'project_name' => 'required|min:4',
            'project_version' => 'required|min:4',
            'project_status' => 'required|min:4',
            'project_folder_structure' => 'required|string',
            'project_data' => 'required|max:1000000|mimes:zip',
        ]);

        if (!$validation->fails()) {
            $pName = $request->input('project_name');
            $pVersion = $request->input('project_version');
            $pStatus = $request->input('project_status');
            $pFs = $request->input('project_folder_structure');
            $pInfo = $request->input('project_additional_info');
            $pFiles = $request->project_data;

            $checkName = Project::where('name', $pName)->first();
            if ($checkName == NULL) {
                $wordlist = array("accelerator", "accordion", "account", "accountant", "acknowledgment", "acoustic", "acrylic", "act", "action", "active", "activity", "actor", "actress", "adapter", "addition", "address", "adjustment", "adult", "advantage", "advertisement", "advice", "afghanistan", "africa", "aftermath", "afternoon", "aftershave", "afterthought", "age", "agenda", "agreement", "air", "airbus", "airmail", "airplane", "airport", "airship", "alarm", "albatross", "alcohol", "algebra", "algeria", "alibi", "alley", "alligator", "alloy", "almanac", "alphabet", "alto", "aluminium", "aluminum", "ambulance", "america", "amount", "amusement", "anatomy", "anethesiologist", "anger", "angle", "angora", "animal", "anime", "ankle", "answer", "ant", "antarctica", "anteater", "antelope", "anthony", "anthropology", "apartment", "apology", "apparatus", "apparel", "appeal", "appendix", "apple", "appliance", "approval", "april", "aquarius", "arch", "archaeology", "archeology", "archer", "architecture", "area", "argentina", "argument", "aries", "arithmetic", "arm", "armadillo", "armchair", "armenian", "army", "arrow", "art", "ash", "ashtray", "asia", "asparagus", "asphalt", "asterisk", "astronomy", "athlete", "atm", "atom", "attack", "attempt", "attention", "attic", "attraction", "august", "aunt", "australia", "australian", "author", "authorisation", "authority", "authorization", "avenue", "babies", "baboon", "baby", "back", "backbone", "bacon", "badge", "badger", "bag", "bagel", "bagpipe", "bail", "bait", "baker", "bakery", "balance", "balinese", "ball", "balloon", "bamboo", "banana", "band", "bandana", "bangladesh", "bangle", "banjo", "bank", "bankbook", "banker", "bar", "barbara", "barber", "barge", "baritone", "barometer", "base", "baseball", "basement", "basin", "basket", "basketball", "bass", "bassoon", "bat", "bath", "bathroom", "bathtub", "battery", "battle", "bay", "beach", "bead", "beam", "bean", "bear", "beard", "beast", "beat", "beautician", "beauty", "beaver", "bed", "bedroom", "bee", "beech", "beef", "beer", "beet", "beetle", "beggar", "beginner", "begonia", "behavior", "belgian", "belief", "believe", "bell", "belt", "bench", "bengal", "beret", "berry", "bestseller", "betty", "bibliography", "bicycle", "bike", "bill", "billboard", "biology", "biplane", "birch", "bird", "birth", "birthday", "bit", "bite", "black", "bladder", "blade", "blanket", "blinker", "blizzard", "block", "blood", "blouse", "blow", "blowgun", "blue", "board", "boat", "bobcat", "body", "bolt", "bomb", "bomber", "bone", "bongo", "bonsai", "book", "bookcase", "booklet", "boot", "border", "botany", "bottle", "bottom", "boundary", "bow", "bowl", "bowling", "box", "boy", "bra", "brace", "bracket", "brain", "brake", "branch", "brand", "brandy", "brass", "brazil", "bread", "break", "breakfast", "breath", "brian", "brick", "bridge", "british", "broccoli", "brochure", "broker", "bronze", "brother", "brother-in-law", "brow", "brown", "brush", "bubble", "bucket", "budget", "buffer", "buffet", "bugle", "building", "bulb", "bull", "bulldozer", "bumper", "bun", "burglar", "burma", "burn", "burst", "bus", "bush", "business", "butane", "butcher", "butter", "button", "buzzard", "c-clamp", "cabbage", "cabinet", "cable", "cactus", "cafe", "cake", "calculator", "calculus", "calendar", "calf", "call", "camel", "camera", "camp", "can", "canada", "canadian", "cancer", "candle", "cannon", "canoe", "canvas", "cap", "capital", "cappelletti", "capricorn", "captain", "caption", "car", "caravan", "carbon", "card", "cardboard", "cardigan", "care", "carnation", "carol", "carp", "carpenter", "carriage", "carrot", "cart", "cartoon", "case", "cast", "castanet", "cat", "catamaran", "caterpillar", "cathedral", "catsup", "cattle", "cauliflower", "cause", "caution", "cave", "cd", "ceiling", "celery", "celeste", "cell", "cellar", "cello", "celsius", "cement", "cemetery", "cent", "centimeter", "century", "ceramic", "cereal", "certification", "chain", "chair", "chalk", "chance", "change", "channel", "character", "chard", "charles", "chauffeur", "check", "cheek", "cheese", "cheetah", "chef", "chemistry", "cheque", "cherries", "cherry", "chess", "chest", "chick", "chicken", "chicory", "chief", "child", "children", "chill", "chime", "chimpanzee", "chin", "china", "chinese", "chive", "chocolate", "chord", "christmas", "christopher", "chronometer", "church", "cicada", "cinema", "circle", "circulation", "cirrus", "citizenship", "city", "clam", "clarinet", "class", "claus", "clave", "clef", "clerk", "click", "client", "climb", "clipper", "cloakroom", "clock", "close", "closet", "cloth", "cloud", "cloudy", "clover", "club", "clutch", "coach", "coal", "coast", "coat", "cobweb", "cockroach", "cocktail", "cocoa", "cod", "coffee", "coil", "coin", "coke", "cold", "collar", "college", "collision", "colombia", "colon", "colony", "color", "colt", "column", "columnist", "comb", "comfort", "comic", "comma", "command", "commission", "committee", "community", "company", "comparison", "competition", "competitor", "composer", "composition", "computer", "condition", "condor", "cone", "confirmation", "conga", "congo", "conifer", "connection", "consonant", "continent", "control", "cook", "cooking", "copper", "copy", "copyright", "cord", "cork", "cormorant", "corn", "cornet", "correspondent", "cost", "cotton", "couch", "cougar", "cough", "country", "course", "court", "cousin", "cover", "cow", "cowbell", "crab", "crack", "cracker", "craftsman", "crate", "crawdad", "crayfish", "crayon", "cream", "creator", "creature", "credit", "creditor", "creek", "crib", "cricket", "crime", "criminal", "crocodile", "crocus", "croissant", "crook", "crop", "cross", "crow", "crowd", "crown", "crush", "cry", "cub", "cuban", "cucumber", "cultivator", "cup", "cupboard", "cupcake", "curler", "currency", "current", "curtain", "curve", "cushion", "custard", "customer", "cut", "cuticle", "cycle", "cyclone", "cylinder", "cymbal", "dad", "daffodil", "dahlia", "daisy", "damage", "dance", "dancer", "danger", "daniel", "dash", "dashboard", "database", "date", "daughter", "david", "day", "dead", "deadline", "deal", "death", "deborah", "debt", "debtor", "decade", "december", "decimal", "decision", "decrease", "dedication", "deer", "defense", "deficit", "degree", "delete", "delivery", "den", "denim", "dentist", "deodorant", "department", "deposit", "description", "desert", "design", "desire", "desk", "dessert", "destruction", "detail", "detective", "development", "dew", "diamond", "diaphragm", "dibble", "dictionary", "dietician", "difference", "digestion", "digger", "digital", "dill", "dime", "dimple", "dinghy", "dinner", "dinosaur", "diploma", "dipstick", "direction", "dirt", "disadvantage", "discovery", "discussion", "disease", "disgust", "dish", "distance", "distribution", "distributor", "diving", "division", "divorced", "dock", "doctor", "dog", "dogsled", "doll", "dollar", "dolphin", "domain", "donald", "donkey", "donna", "door", "dorothy", "double", "doubt", "downtown", "dragon", "dragonfly", "drain", "drake", "drama", "draw", "drawbridge", "drawer", "dream", "dredger", "dress", "dresser", "dressing", "drill", "drink", "drive", "driver", "driving", "drizzle", "drop", "drug", "drum", "dry", "dryer", "duck", "duckling", "dugout", "dungeon", "dust", "eagle", "ear", "earth", "earthquake", "ease", "east", "edge", "edger", "editor", "editorial", "education", "edward", "eel", "effect", "egg", "eggnog", "eggplant", "egypt", "eight", "elbow", "element", "elephant", "elizabeth", "ellipse", "emery", "employee", "employer", "encyclopedia", "end", "enemy", "energy", "engine", "engineer", "engineering", "english", "enquiry", "entrance", "environment", "epoch", "epoxy", "equinox", "equipment", "era", "error", "estimate", "ethernet", "ethiopia", "euphonium", "europe", "evening", "event", "ex-husband", "ex-wife", "examination", "example", "exchange", "exclamation", "exhaust", "existence", "expansion", "experience", "expert", "explanation", "eye", "eyebrow", "eyelash", "eyeliner", "face", "facilities", "fact", "factory", "fahrenheit", "fairies", "fall", "family", "fan", "fang", "farm", "farmer", "fat", "father", "father-in-law", "faucet", "fear", "feast", "feather", "feature", "february", "fedelini", "feedback", "feeling", "feet", "felony", "female", "fender", "ferry", "ferryboat", "fertilizer", "fiber", "fiberglass", "fibre", "fiction", "field", "fifth", "fight", "fighter", "file", "find", "fine", "finger", "fir", "fire", "fired", "fireman", "fireplace", "firewall", "fish", "fisherman", "flag", "flame", "flare", "flat", "flavor", "flax", "flesh", "flight", "flock", "flood", "floor", "flower", "flugelhorn", "flute", "fly", "foam", "fog", "fold", "font", "food", "foot", "football", "footnote", "force", "forecast", "forehead", "forest", "forgery", "fork", "form", "format", "fortnight", "foundation", "fountain", "fowl", "fox", "foxglove", "fragrance", "frame", "france", "freckle", "freeze", "freezer", "freighter", "french", "freon", "friction", "friday", "fridge", "friend", "frog", "front", "frost", "frown", "fruit", "fuel", "fur", "furniture", "galley", "gallon", "game", "gander", "garage", "garden", "garlic", "gas", "gasoline", "gate", "gateway", "gauge", "gazelle", "gear", "gearshift", "geese", "gemini", "gender", "geography", "geology", "geometry", "george", "geranium", "german", "germany", "ghana", "ghost", "giant", "giraffe", "girdle", "girl", "gladiolus", "glass", "glider", "gliding", "glockenspiel", "glove", "glue", "goal", "goat", "gold", "goldfish", "golf", "gondola", "gong", "good-bye", "goose", "gore-tex", "gorilla", "gosling", "government", "governor", "grade", "grain", "gram", "granddaughter", "grandfather", "grandmother", "grandson", "grape", "graphic", "grass", "grasshopper", "gray", "grease", "great-grandfather", "great-grandmother", "greece", "greek", "green", "grenade", "grey", "grill", "grip", "ground", "group", "grouse", "growth", "guarantee", "guatemalan", "guide", "guilty", "guitar", "gum", "gun", "gym", "gymnast", "hacksaw", "hail",
                "hair", "haircut", "half-brother", "half-sister", "halibut", "hall", "hallway", "hamburger", "hammer", "hamster", "hand", "handball", "handicap", "handle", "handsaw", "harbor", "hardboard", "hardcover", "hardhat", "hardware", "harmonica", "harmony", "harp", "hat", "hate", "hawk", "head", "headlight", "headline", "health", "hearing", "heart", "heat", "heaven", "hedge", "height", "helen", "helicopter", "helium", "hell", "helmet", "help", "hemp", "hen", "heron", "herring", "hexagon", "hill", "himalayan", "hip", "hippopotamus", "history", "hobbies", "hockey", "hoe", "hole", "holiday", "home", "honey", "hood", "hook", "hope", "horn", "horse", "hose", "hospital", "hot", "hour", "hourglass", "house", "hovercraft", "hub", "hubcap", "humidity", "humor", "hurricane", "hyacinth", "hydrant", "hydrofoil", "hydrogen", "hyena", "hygienic", "ice", "icebreaker", "icicle", "icon", "idea", "ikebana", "illegal", "imprisonment", "improvement", "impulse", "inch", "income", "increase", "index", "india", "indonesia", "industry", "ink", "innocent", "input", "insect", "instruction", "instrument", "insulation", "insurance", "interactive", "interest", "internet", "interviewer", "intestine", "invention", "inventory", "invoice", "iran", "iraq", "iris", "iron", "island", "israel", "italian", "italy", "jacket", "jaguar", "jail", "jam", "james", "january", "japan", "japanese", "jar", "jasmine", "jason", "jaw", "jeans", "jeep", "jeff", "jelly", "jellyfish", "jennifer", "jet", "jewel", "jogging", "john", "join", "joke", "joseph", "journey", "judge", "judo", "juice", "july", "jumbo", "jump", "jumper", "june", "jury", "justice", "jute", "kale", "kamikaze", "kangaroo", "karate", "karen", "kayak", "kendo", "kenneth", "kenya", "ketchup", "kettle", "kettledrum", "kevin", "key", "keyboard", "keyboarding", "kick", "kidney", "kilogram", "kilometer", "kimberly", "kiss", "kitchen", "kite", "kitten", "kitty", "knee", "knickers", "knife", "knight", "knot", "knowledge", "kohlrabi", "korean", "laborer", "lace", "ladybug", "lake", "lamb", "lamp", "lan", "land", "landmine", "language", "larch", "lasagna", "latency", "latex", "lathe", "laugh", "laundry", "laura", "law", "lawyer", "layer", "lead", "leaf", "learning", "leather", "leek", "leg", "legal", "lemonade", "lentil", "leo", "leopard", "letter", "lettuce", "level", "libra", "library", "license", "lier", "lift", "light", "lightning", "lilac", "lily", "limit", "linda", "line", "linen", "link", "lion", "lip", "lipstick", "liquid", "liquor", "lisa", "list", "literature", "litter", "liver", "lizard", "llama", "loaf", "loan", "lobster", "lock", "locket", "locust", "look", "loss", "lotion", "love", "low", "lumber", "lunch", "lunchroom", "lung", "lunge", "lute", "luttuce", "lycra", "lynx", "lyocell", "lyre", "lyric", "macaroni", "machine", "macrame", "magazine", "magic", "magician", "maid", "mail", "mailbox", "mailman", "makeup", "malaysia", "male", "mall", "mallet", "man", "manager", "mandolin", "manicure", "manx", "map", "maple", "maraca", "marble", "march", "margaret", "margin", "maria", "marimba", "mark", "market", "married", "mary", "mascara", "mask", "mass", "match", "math", "mattock", "may", "mayonnaise", "meal", "measure", "meat", "mechanic", "medicine", "meeting", "melody", "memory", "men", "menu", "mercury", "message", "metal", "meteorology", "meter", "methane", "mexican", "mexico", "mice", "michael", "michelle", "microwave", "middle", "mile", "milk", "milkshake", "millennium", "millimeter", "millisecond", "mimosa", "mind", "mine", "mini-skirt", "minibus", "minister", "mint", "minute", "mirror", "missile", "mist", "mistake", "mitten", "moat", "modem", "mole", "mom", "monday", "money", "monkey", "month", "moon", "morning", "morocco", "mosque", "mosquito", "mother", "mother-in-law", "motion", "motorboat", "motorcycle", "mountain", "mouse", "moustache", "mouth", "move", "multi-hop", "multimedia", "muscle", "museum", "music", "musician", "mustard", "myanmar", "nail", "name", "nancy", "napkin", "narcissus", "nation", "neck", "need", "needle", "neon", "nepal", "nephew", "nerve", "nest", "net", "network", "news", "newsprint", "newsstand", "nic", "nickel", "niece", "nigeria", "night", "nitrogen", "node", "noise", "noodle", "north", "north america", "north korea", "norwegian", "nose", "note", "notebook", "notify", "novel", "november", "number", "numeric", "nurse", "nut", "nylon", "oak", "oatmeal", "objective", "oboe", "observation", "occupation", "ocean", "ocelot", "octagon", "octave", "october", "octopus", "odometer", "offence", "offer", "office", "oil", "okra", "olive", "onion", "open", "opera", "operation", "ophthalmologist", "opinion", "option", "orange", "orchestra", "orchid", "order", "organ", "organisation", "organization", "ornament", "ostrich", "otter", "ounce", "output", "outrigger", "oval", "oven", "overcoat", "owl", "owner", "ox", "oxygen", "oyster", "package", "packet", "page", "pail", "pain", "paint", "pair", "pajama", "pakistan", "palm", "pamphlet", "pan", "pancake", "pancreas", "panda", "pansy", "panther", "panties", "pantry", "pants", "panty", "pantyhose", "paper", "paperback", "parade", "parallelogram", "parcel", "parent", "parentheses", "park", "parrot", "parsnip", "part", "particle", "partner", "partridge", "party", "passbook", "passenger", "passive", "pasta", "paste", "pastor", "pastry", "patch", "path", "patient", "patio", "patricia", "paul", "payment", "pea", "peace", "peak", "peanut", "pear", "pedestrian", "pediatrician", "peen", "peer-to-peer", "pelican", "pen", "penalty", "pencil", "pendulum", "pentagon", "peony", "pepper", "perch", "perfume", "period", "periodical", "peripheral", "permission", "persian", "person", "peru", "pest", "pet", "pharmacist", "pheasant", "philippines", "philosophy", "phone", "physician", "piano", "piccolo", "pickle", "picture", "pie", "pig", "pigeon", "pike", "pillow", "pilot", "pimple", "pin", "pine", "ping", "pink", "pint", "pipe", "pisces", "pizza", "place", "plain", "plane", "planet", "plant", "plantation", "plaster", "plasterboard", "plastic", "plate", "platinum", "play", "playground", "playroom", "pleasure", "plier", "plot", "plough", "plow", "plywood", "pocket", "poet", "point", "poison", "poland", "police", "policeman", "polish", "politician", "pollution", "polo", "polyester", "pond", "popcorn", "poppy", "population", "porch", "porcupine", "port", "porter", "position", "possibility", "postage", "postbox", "pot", "potato", "poultry", "pound", "powder", "power", "precipitation", "preface", "prepared", "pressure", "price", "priest", "print", "printer", "prison", "probation", "process", "processing", "produce", "product", "production", "professor", "profit", "promotion", "propane", "property", "prose", "prosecution", "protest", "protocol", "pruner", "psychiatrist", "psychology", "ptarmigan", "puffin", "pull", "puma", "pump", "pumpkin", "punch", "punishment", "puppy", "purchase", "purple", "purpose", "push", "pvc", "pyjama", "pyramid", "quail", "quality", "quart", "quarter", "quartz", "queen", "question", "quicksand", "quiet", "quill", "quilt", "quince", "quit", "quiver", "quotation", "rabbi", "rabbit", "racing", "radar", "radiator", "radio", "radish", "raft", "rail", "railway", "rain", "rainbow", "raincoat", "rainstorm", "rake", "ramie", "random", "range", "rat", "rate", "raven", "ravioli", "ray", "rayon", "reaction", "reading", "reason", "receipt", "recess", "record", "recorder", "rectangle", "red", "reduction", "refrigerator", "refund", "regret", "reindeer", "relation", "relative", "religion", "relish", "reminder", "repair", "replace", "report", "representative", "request", "resolution", "respect", "responsibility", "rest", "restaurant", "result", "retailer", "revolve", "revolver", "reward", "rhinoceros", "rhythm", "rice", "richard", "riddle", "rifle", "ring", "rise", "risk", "river", "riverbed", "road", "roadway", "roast", "robert", "robin", "rock", "rocket", "rod", "roll", "romania", "romanian", "ronald", "roof", "room", "rooster", "root", "rose", "rotate", "route", "router", "rowboat", "rub", "rubber", "rugby", "rule", "run", "russia", "russian", "rutabaga", "ruth", "sack", "sagittarius", "sail", "sailboat", "sailor", "salad", "salary", "sale", "salesman", "salmon", "salt", "sampan", "samurai", "sand", "sandra", "sandwich", "santa", "sarah", "sardine", "satin", "saturday", "sauce", "saudi arabia", "sausage", "save", "saw", "saxophone", "scale", "scallion", "scanner", "scarecrow", "scarf", "scene", "scent", "schedule", "school", "science", "scissors", "scooter", "scorpio", "scorpion", "scraper", "screen", "screw", "screwdriver", "sea", "seagull", "seal", "seaplane", "search", "seashore", "season", "seat", "second", "secretary", "secure", "security", "seed", "seeder", "segment", "select", "selection", "self", "semicircle", "semicolon", "sense", "sentence", "separated", "september", "servant", "server", "session", "sex", "shade", "shadow", "shake", "shallot", "shame", "shampoo", "shape", "share", "shark", "sharon", "shears", "sheep", "sheet", "shelf", "shell", "shield", "shingle", "ship", "shirt", "shock", "shoe", "shoemaker", "shop", "shorts", "shoulder", "shovel", "show", "shrimp", "shrine", "siamese", "siberian", "side", "sideboard", "sidecar", "sidewalk", "sign", "signature", "silica", "silk", "silver", "sing", "singer", "single", "sink", "sister", "sister-in-law", "size", "skate", "skiing", "skill", "skin", "skirt", "sky", "slash", "slave", "sled", "sleep", "sleet", "slice", "slime", "slip", "slipper", "slope", "smash", "smell", "smile", "smoke", "snail", "snake", "sneeze", "snow", "snowboarding", "snowflake", "snowman", "snowplow", "snowstorm", "soap", "soccer", "society", "sociology", "sock", "soda", "sofa", "softball", "softdrink", "software", "soil", "soldier", "son", "song", "soprano", "sort", "sound", "soup", "sousaphone", "south africa", "south america", "south korea", "soy", "soybean", "space", "spade", "spaghetti", "spain", "spandex", "spark", "sparrow", "spear", "specialist", "speedboat", "sphere", "sphynx", "spider", "spike", "spinach",
                "spleen", "sponge", "spoon", "spot", "spring", "sprout", "spruce", "spy", "square", "squash", "squid", "squirrel", "stage", "staircase", "stamp", "star", "start", "starter", "state", "statement", "station", "statistic", "steam", "steel", "stem", "step", "step-aunt", "step-brother", "step-daughter", "step-father", "step-grandfather", "step-grandmother", "step-mother", "step-sister", "step-son", "step-uncle", "stepdaughter", "stepmother", "stepson", "steven", "stew", "stick", "stinger", "stitch", "stock", "stocking", "stomach", "stone", "stool", "stop", "stopsign", "stopwatch", "store", "storm", "story", "stove", "stranger", "straw", "stream", "street", "streetcar", "stretch", "string", "structure", "study", "sturgeon", "submarine", "substance", "subway", "success", "sudan", "suede", "sugar", "suggestion", "suit", "summer", "sun", "sunday", "sundial", "sunflower", "sunshine", "supermarket", "supply", "support", "surfboard", "surgeon", "surname", "surprise", "susan", "sushi", "swallow", "swamp", "swan", "sweater", "sweatshirt", "sweatshop", "swedish", "sweets", "swim", "swimming", "swing", "swiss", "switch", "sword", "swordfish", "sycamore", "syria", "syrup", "system", "t-shirt", "table", "tablecloth", "tabletop", "tachometer", "tadpole", "tail", "tailor", "taiwan", "talk", "tank", "tanker", "tanzania", "target", "taste", "taurus", "tax", "taxi", "taxicab", "tea", "teacher", "teaching", "team", "technician", "teeth", "television", "teller", "temper", "temperature", "temple", "tempo", "tendency", "tennis", "tenor", "tent", "territory", "test", "text", "textbook", "texture", "thailand", "theater", "theory", "thermometer", "thing", "thistle", "thomas", "thought", "thread", "thrill", "throat", "throne", "thumb", "thunder", "thunderstorm", "thursday", "ticket", "tie", "tiger", "tights", "tile", "timbale", "time", "timer", "timpani", "tin", "tip", "tire", "titanium", "title", "toad", "toast", "toe", "toenail", "toilet", "tom-tom", "tomato", "ton", "tongue", "tooth", "toothbrush", "toothpaste", "top", "tornado", "tortellini", "tortoise", "touch", "tower", "town", "toy", "tractor", "trade", "traffic", "trail", "train", "tramp", "transaction", "transmission", "transport", "trapezoid", "tray", "treatment", "tree", "trial", "triangle", "trick", "trigonometry", "trip", "trombone", "trouble", "trousers", "trout", "trowel", "truck", "trumpet", "trunk", "tsunami", "tub", "tuba", "tuesday", "tugboat", "tulip", "tuna", "tune", "turkey", "turkish", "turn", "turnip", "turnover", "turret", "turtle", "tv", "twig", "twilight", "twine", "twist", "typhoon", "tyvek", "uganda", "ukraine", "ukrainian", "umbrella", "uncle", "underclothes", "underpants", "undershirt", "underwear", "unit", "united kingdom", "unshielded", "use", "utensil", "uzbekistan", "vacation", "vacuum", "valley", "value", "van", "var verbs = [aardvark", "vase", "vault", "vegetable", "vegetarian", "veil", "vein", "velvet", "venezuela", "venezuelan", "verdict", "vermicelli", "verse", "vessel", "vest", "veterinarian", "vibraphone", "vietnam", "view", "vinyl", "viola", "violet", "violin", "virgo", "viscose", "vise", "vision", "visitor", "voice", "volcano", "volleyball", "voyage", "vulture", "waiter", "waitress", "walk", "wall", "wallaby", "wallet", "walrus", "war", "warm", "wash", "washer", "wasp", "waste", "watch", "watchmaker", "water", "waterfall", "wave", "wax", "way", "wealth", "weapon", "weasel", "weather", "wedge", "wednesday", "weed", "weeder", "week", "weight", "whale", "wheel", "whip", "whiskey", "whistle", "white", "wholesaler", "whorl", "wilderness", "william", "willow", "wind", "windchime", "window", "windscreen", "windshield", "wine", "wing", "winter", "wire", "wish", "witch", "withdrawal", "witness", "wolf", "woman", "women", "wood", "wool", "woolen", "word", "work", "workshop", "worm", "wound", "wrecker", "wren", "wrench", "wrinkle", "wrist", "writer", "xylophone", "yacht", "yak", "yam", "yard", "yarn", "year", "yellow", "yew", "yogurt", "yoke", "yugoslavian", "zebra", "zephyr", "zinc", "zipper", "zone", "zoo", "zoology");
                $secretKey = substr(preg_replace('/[^\w]/','',base64_encode(sha1(rand(0, 100000)))),0,12);
                $accessUser = $wordlist[array_rand($wordlist)];
                $accessPass = $wordlist[array_rand($wordlist)];

                $fileResult = Self::unpackProject($pFiles, $pFs, $pName, $secretKey, $accessUser, $accessPass);

                if (!is_string($fileResult) && $fileResult) {
                    $project = new Project();
                    $project->name = $pName;
                    $project->version = $pVersion;
                    $project->status = $pStatus;
                    $project->folder_structure = $pFs;
                    $project->additional_info = $pInfo;
                    $project->secretKey = $secretKey;
                    $project->accessUser = $accessUser;
                    $project->accessPass = $accessPass;
                    $result = $project->Save();

                    if ($result) {
                        return view('dashboard.projectCompletion', [
                            'User' => Auth::user(),
                            'activePage' => 'projects',
                            'Project' => Project::where('name', $project->name)->first()
                        ]);
                    } else {
                        Session::flash('submitError', "Your project could not be created.");
                        return Redirect::back();
                    }
                } else {
                    if (!is_string($fileResult)) {
                        Session::flash('submitError', "There was an error uploading your project data.");
                        return Redirect::back();
                    }
                    Session::flash('submitError', $fileResult);
                    return Redirect::back();
                }
            } else {
                Session::flash('submitError', "A project with that name already exists.");
                return Redirect::back();
            }
        } else {
            Session::flash('submitError', "Your inputs could not be validated. Please try again.");
            return Redirect::back();
        }
    }

    // Edit project
    public function editProject(Request $request, $projectName) {
        return view('dashboard.editProject', [
            'User' => Auth::user(),
            'activePage' => 'projects',
            'Project' => Project::where('name', $projectName)->first()
        ]);
    }

    // Edit project
    public function editEnv(Request $request, $projectName) {
        $project = Project::where('name', $projectName)->first();
        $secret = env('CUSTOM_HOST_USER');
        $envFile = "/home/$secret/domains/tomvdbroecke.com/Projects/$project->name/.env";

        return view('dashboard.editEnv', [
            'User' => Auth::user(),
            'activePage' => 'projects',
            'Project' => $project,
            'envFile' => $envFile
        ]);
    }

    // Edit project
    public function updateEnv(Request $request) {
        $validation = Validator::make($request->all(), [
            'project_id' => 'required|numeric',
            'env-text' => 'required',
        ]);

        if (!$validation->fails()) {
            $pId = $request->input('project_id');
            $envText = $request->input('env-text');

            if ($request->has('env-submit')) {
                $secret = env('CUSTOM_HOST_USER');
                $project = Project::where('id', $pId)->first();

                $stream = fopen("/home/$secret/domains/tomvdbroecke.com/Projects/$project->name/.env", 'w');
                fwrite($stream, $envText);
                fclose($stream);
                
                return view('dashboard.projectEnvUpdated', [
                    'User' => Auth::user(),
                    'activePage' => 'projects',
                    'Project' => $project
                ]);
            }
    
            if ($request->has('env-cancel')) {
                $project = Project::where('id', $pId)->first();
                return redirect("/dashboard/projects/edit/$project->name");
            }
        }
    }

    // Update project
    public function updateProject(Request $request) {
        if ($request->has('update_project')) {
            $validation = Validator::make($request->all(), [
                'project_id' => 'required|numeric',
                //'project_name' => 'required|min:4',
                'project_version' => 'required|min:4',
                'project_status' => 'required|min:4',
                'project_data' => 'nullable|max:1000000|mimes:zip',
            ]);

            if (!$validation->fails()) {
                $pId = $request->input('project_id');
                //$pName = $request->input('project_name');
                $pVersion = $request->input('project_version');
                $pStatus = $request->input('project_status');
                $pInfo = $request->input('project_additional_info');
                if ($request->file('project_data') != NULL) {
                    $pFiles = $request->project_data;
                }
    
                //$checkName = Project::where('name', $pName)->first();
                //if ($checkName->id == $pId) {
                    $project = Project::where('id', $pId)->first();

                    if ($request->file('project_data') != NULL) {
                        $fileResult = Self::overwriteProject($pFiles, $project->folder_structure, $project->name, $project->secretKey, $project->accessUser, $project->accessPass);
                    } else {
                        $fileResult = true;
                    }

                    if (!is_string($fileResult) && $fileResult) {
                        $updateArray = array();
                        //$updateArray['name'] = $pName;
                        $updateArray['version'] = $pVersion;
                        $updateArray['status'] = $pStatus;
                        $updateArray['additional_info'] = $pInfo;
                        $result = $project->Update($updateArray);
        
                        if ($result) {
                            if ($request->file('project_data') != NULL) {
                                return view('dashboard.projectDataUpdated', [
                                    'User' => Auth::user(),
                                    'activePage' => 'projects',
                                    'Project' => Project::where('name', $project->name)->first()
                                ]);
                            }
                            return redirect('/dashboard/projects');
                        } else {
                            Session::flash('submitError', "Your project could not be created.");
                            return Redirect::back();
                        }
                    } else {
                        if (!is_string($fileResult)) {
                            Session::flash('submitError', "There was an error uploading your project data.");
                            return Redirect::back();
                        }
                        Session::flash('submitError', $fileResult);
                        return Redirect::back();
                    }
                //} else {
                //   Session::flash('submitError', "A project with that name already exists.");
                //    return Redirect::back();
                //}
            } else {
                Session::flash('submitError', $validation->errors()->first());
                return Redirect::back();
            }
        }

        if ($request->has('delete_project')) {
            $validation = Validator::make($request->all(), [
                'project_id' => 'required|numeric',
            ]);

            if (!$validation->fails()) {
                $pId = $request->input('project_id');

                $project = Project::where('id', $pId)->first();
                $result = $project->delete();

                if ($result) {
                    // Remove project files
                    $secret = env('CUSTOM_HOST_USER');
                    if ($project->folder_structure == "laravel") {
                        Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/Projects/$project->name", true);
                        Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$project->name" .'_public_' ."$project->secretKey/", true);
                        Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$project->name."_public_$project->secretKey", true);
                    }
                    if ($project->folder_structure == "standard") {
                        Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$project->name" .'_public_' ."$project->secretKey/", true);
                        Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$project->name."_public_$project->secretKey", true);
                    }

                    // Remove project permissions from users
                    $users = User::all();
                    foreach ($users as $user) {
                        $projects = $user->Projects();

                        if ($projects != NULL) {
                            if ($projects[0] != 'all') {
                                for ($i = 0; $i < sizeof($projects); $i++) {
                                    if ($projects[$i] == $pId) {
                                        unset($projects[$i]);
                                    }
                                }

                                if (sizeof($projects) > 0) {
                                    $user->permitted_projects = json_encode($projects);
                                } else {
                                    $user->permitted_projects = NULL;
                                }
                                $user->Save();
                            }
                        }
                    }

                    return view('dashboard.projectDeletion', [
                        'User' => Auth::user(),
                        'activePage' => 'projects'
                    ]);
                } else {
                    Session::flash('submitError', "Your project could not be removed.");
                    return Redirect::back();
                }
            } else {
                Session::flash('submitError', "Your project could not be removed.");
                return Redirect::back();
            }
        }

        return view('dashboard.projects', [
            'User' => Auth::user(),
            'activePage' => 'projects'
        ]);
    }

    // Console
    public function viewConsole() {
        $commands = array();
        $file = file(storage_path('consoleLog.txt'));
        for ($i = max(0, count($file)-50); $i < count($file); $i++) {
            array_push($commands, $file[$i]);
        }

        return view('dashboard.console', [
            'User' => Auth::user(),
            'activePage' => 'console',
            'commands' => $commands
        ]);
    }

    // Console log
    public function viewConsoleLog() {
        return view('dashboard.logView');
    }

    // Enter console
    public function enterConsole(Request $request) {
        $input = $request->input('consoleInput');
        $cutInput = explode(" ", $input);
        $command = $cutInput[0];

        $timestamp = date("d-m-Y H:i:s");

        if (Self::isArtisan($command)) {
            $results = Self::executeCustomCommand($command, $cutInput);

            if (substr($results[0], 0, 5) === "rdir:") {
                return redirect(substr($results[0], 5));
            }

            $stream = fopen(storage_path('consoleLog.txt'), 'a');
            fwrite($stream, $timestamp . " Input: ". $input . "\n");
            foreach($results as $result) {
                fwrite($stream, $timestamp . " " . $result ."\n");
            }
            fclose($stream);
        } else {
            // Check if command is whitelisted
            if (in_array($command, Config::get('commands.whitelist'))) {
                $commandArray = Self::returnValidCommandArray($cutInput);

                $stream = fopen(storage_path('consoleLog.txt'), 'a');
                $tempFile = fopen(storage_path('tempConsoleLog.txt'), 'w');
                Artisan::call($command, $commandArray, new StreamOutput($tempFile));
                fclose($tempFile);
                $lines = file(storage_path('tempConsoleLog.txt'), FILE_IGNORE_NEW_LINES);
                fwrite($stream, $timestamp . " Input: ". $input . "\n");
                foreach($lines as $line) {
                    fwrite($stream, $timestamp . " " . $line . "\n");
                }
                fclose($stream);
                unlink(storage_path('tempConsoleLog.txt'));
            } else {
                $stream = fopen(storage_path('consoleLog.txt'), 'a');
                fwrite($stream, $timestamp . " Input: ". $input . "\n");
                fwrite($stream, $timestamp . " Error: Artisan command not whitelisted!\n");
                fclose($stream);
            }
        }

        return redirect('/dashboard/console');
    }

    // Check if command is custom
    public function isArtisan($command) {
        if (in_array($command, Config::get('commands.whitelist'))) {
            return false;
        } else {
            return true;
        }
    }

    // Return valid command array
    public function returnValidCommandArray($cutInput) {
        $commandArray = array();

        // MAKE:CONTROLLER
        if ($cutInput[0] == "make:controller") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($i == 1) {
                    $commandArray["name"] = $cutInput[$i];
                }
            }
        }

        // MAKE:MODEL
        if ($cutInput[0] == "make:model") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($i == 1) {
                    $commandArray["name"] = $cutInput[$i];
                }
                if ($cutInput[$i] == "--migration" || $cutInput[$i] == "-m") {
                    $commandArray["--migration"] = true;
                }
                if ($cutInput[$i] == "--controller"  || $cutInput[$i] == "-c") {
                    $commandArray["--controller"] = true;
                }
                if ($cutInput[$i] == "--resource"  || $cutInput[$i] == "-r") {
                    $commandArray["--resource"] = true;
                }
            }
        }

        // ANY MIGRATE
        if ($cutInput[0] == "migrate" || $cutInput[0] == "migrate:fresh" || $cutInput[0] == "migrate:install" || $cutInput[0] == "migrate:refresh" || $cutInput[0] == "migrate:reset" || $cutInput[0] == "migrate:rollback" || $cutInput[0] == "migrate:status") {
            for ($i = 0; $i < sizeof($cutInput); $i++) {
                if ($cutInput[$i] == "--force"  || $cutInput[$i] == "-f") {
                    $commandArray["--force"] = true;
                }
            }
        }

        return $commandArray;
    }

    // Execute custom command
    public function executeCustomCommand($command, $cutInput) {
        switch ($command) {
            case "test":
                return array("Custom commands are enabled!");
                break;
            case "log:clear":
                file_put_contents(storage_path('consoleLog.txt'), "");
                return array("Contents of the Console Log have been cleared!");
                break;
            case "log:view":
                return array("rdir:/dashboard/consoleLog");
                break;
            case "setbatch":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["id"] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray["number"] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: setbatch [migration ID] [batch number]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'number' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $entry = DB::table('migrations')->where('id', $commandArray['id'])->first();
                    if ($entry != NULL) {
                        DB::table('migrations')
                            ->where('id', $commandArray['id'])
                            ->update(['batch' => $commandArray['number']]);
                        return array("Batch number of $entry->migration has been set to ".$commandArray["number"]);
                        break;
                    } else {
                        return array("Error: No migration with ID: ".$commandArray['id']." was found.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: setbatch [migration ID] [batch number]");
                    break;
                }
            case "getbatch":
                $entries = DB::table('migrations')->get();
                if ($entries != NULL) {
                    $returnArray = array();
                    foreach ($entries as $entry) {
                        array_push($returnArray, "$entry->id | $entry->migration | $entry->batch");
                    }
                    return $returnArray;
                    break;
                } else {
                    return array("No migrations found.");
                    break;
                }
            case "commands":
                $returnArray = array();
                array_push($returnArray, "Artisan Commands:");
                foreach (Config::get('commands.whitelist') as $command) {
                    array_push($returnArray, "-$command");
                }
                array_push($returnArray, "Custom Commands:");
                foreach (Config::get('commands.custom') as $command) {
                    array_push($returnArray, "-$command");
                }
                return $returnArray;
            case "user:get":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:get [searchInput/id/'all']");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["searchInput"] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:get [searchInput/id/'all']");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'searchInput' => 'required',
                ]);

                if (!$validation->fails()) {
                    if ($commandArray['searchInput'] == 'all') {
                        $entries = User::all();
                    } else {
                        $search = $commandArray['searchInput'];
                        $entries = User::where('id', $commandArray['searchInput'])->orWhere(function ($query) use ($search) {
                            $query->where('email', 'LIKE', "%$search%")
                                  ->orWhere('name', 'LIKE', "%$search%");
                        })->get();
                    }

                    if (sizeof($entries) > 0) {
                        $returnArray = array();
                        foreach ($entries as $entry) {
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }
                            array_push($returnArray, "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        }
                        return $returnArray;
                        break;
                    } else {
                        return array("No users found.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:get [searchInput/id/'all']");
                    break;
                }
            case "user:create":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:create [name] [email] [password] [active:true/false] [autoverify:true/false]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["name"] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray["email"] = $cutInput[$i];
                    }
                    if ($i == 3) {
                        $commandArray["password"] = $cutInput[$i];
                    }
                    if ($i == 4) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["active"] = true;
                        } else {
                            $commandArray["active"] = false;
                        }
                    }
                    if ($i == 5) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["autoverify"] = true;
                        } else {
                            $commandArray["autoverify"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:create [name] [email] [password] [active:true/false] [autoverify:true/false]");
                        break;
                    }
                }

                if (!array_key_exists('autoverify', $commandArray)) {
                    $commandArray['autoverify'] = false;
                }
                if (!array_key_exists('active', $commandArray)) {
                    $commandArray['active'] = false;
                }
                $validation = Validator::make($commandArray, [
                    'name' => 'required|min:2',
                    'email' => 'required|email',
                    'password' => 'required|min:4',
                    'active' => 'required|boolean',
                    'autoverify' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = new User();
                    $user->name = $commandArray['name'];
                    $user->password = Hash::make($commandArray['password']);
                    $user->email = $commandArray['email'];
                    $user->active = $commandArray['active'];
                    $result = $user->Save();

                    if ($result) {
                        if ($commandArray['autoverify']) {
                            $user->markEmailAsVerified();
                        } else {
                            $user->sendEmailVerificationNotification();
                        }

                        $entry = User::where('id', $user->id)->first();
                        $active = "inactive";
                        $verified = "unverified";
                        if ($entry->active) {
                            $active = "active";
                        }
                        if ($entry->email_verified_at != NULL) {
                            $verified = "verified";
                        }

                        return array("Success: User has been created.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        break;
                    } else {
                        return array("Error: User could not be created.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:create [name] [email] [password] [active:true/false] [autoverify:true/false]");
                    break;
                }
            case "user:edit:active":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:active [id] [active:true/false]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["active"] = true;
                        } else {
                            $commandArray["active"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:active [id] [active:true/false]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'active' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->active = $commandArray['active'];
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:active [id] [active:true/false]");
                    break;
                }
            case "user:edit:verified":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:verified [id] [verified:true/false]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        if ($cutInput[$i] === 'true') {
                            $commandArray["verified"] = true;
                        } else {
                            $commandArray["verified"] = false;
                        }
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:verified [id] [verified:true/false]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'verified' => 'required|boolean',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        if ($commandArray['verified']) {
                            $user->markEmailAsVerified();
                        } else {
                            $user->email_verified_at = NULL;
                            $result = $user->Save();
                        }

                        $entry = User::where('id', $user->id)->first();
                        $active = "inactive";
                        $verified = "unverified";
                        if ($entry->active) {
                            $active = "active";
                        }
                        if ($entry->email_verified_at != NULL) {
                            $verified = "verified";
                        }

                        return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                        break;
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:active [id] [verified:true/false]");
                    break;
                }
            case "user:edit:name":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:name [id] [name]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['name'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:name [id] [name]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'name' => 'required|min:2',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->name = $commandArray['name'];
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:name [id] [name]");
                    break;
                }
            case "user:edit:email":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:email [id] [email]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['email'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:email [id] [email]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'email' => 'required|email',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->email = $commandArray['email'];
                        $user->email_verified_at = NULL;
                        $result = $user->Save();

                        if ($result) {
                            $user->sendEmailVerificationNotification();

                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:email [id] [email]");
                    break;
                }
            case "user:edit:password":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:edit:password [id] [password]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['password'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:edit:password [id] [password]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'password' => 'required|min:4',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $user->password = Hash::make($commandArray['password']);
                        $result = $user->Save();

                        if ($result) {
                            $entry = User::where('id', $user->id)->first();
                            $active = "inactive";
                            $verified = "unverified";
                            if ($entry->active) {
                                $active = "active";
                            }
                            if ($entry->email_verified_at != NULL) {
                                $verified = "verified";
                            }

                            return array("Success: User has been updated.", "$entry->id | $entry->name | $entry->email | $active | $verified | $entry->permission_rank");
                            break;
                        } else {
                            return array("Error: User could not be updated.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:edit:password [id] [password]");
                    break;
                }
            case "user:delete":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:delete [id]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:delete [id]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $result = $user->delete();

                        if ($result) {
                            return array("Success: User has been removed.");
                            break;
                        } else {
                            return array("Error: User could not be removed.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:delete [id]");
                    break;
                }
            case "user:projects:get":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:projects:get [id]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:projects:get [id]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $perms = $user->Projects();
                        if ($perms != NULL) {
                            if ($perms[0] == 'all') {
                                $projects = Project::all();
                            } else {
                                $projects = Project::whereIn('id', $perms)->get();
                            }

                            if (sizeof($projects) > 0) {
                                $returnArray = array();
                                foreach ($projects as $project) {
                                    array_push($returnArray, "$project->id | $project->name | $project->version | $project->status");
                                }
                                return $returnArray;
                                break;
                            } else {
                                return array("This user does not have any Projects.");
                                break;
                            }
                        } else {
                            return array("This user does not have any Projects.");
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:projects:get [id]");
                    break;
                }
            case "user:projects:add":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:projects:add [id] [projectID]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['projectID'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:projects:add [id] [projectID]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'projectID' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $project = Project::where('id', $commandArray['projectID'])->first();
                        if ($project != NULL) {
                            $currentProjects = $user->Projects();
                            if ($currentProjects != NULL) {
                                if ($currentProjects[0] == 'all') {
                                    return array("This user is an admin, therefore they already have access to all projects.");
                                    break;
                                } else {
                                    // IF USER HAS CURRENT PROJECTS
                                    if (!in_array($commandArray['projectID'], $currentProjects)) {
                                        array_push($currentProjects, $commandArray['projectID']);
                                    } else {
                                        return array("Error: $user->name already has $project->name in their project list.");
                                        break;
                                    }
                                }
                            } else {
                                // IF USER HAS NO CURRENT PROJECTS
                                $currentProjects = array($commandArray['projectID']);
                            }
                            $user->permitted_projects = json_encode($currentProjects);
                            $result = $user->Save();

                            if ($result) {
                                return array("Success: $project->name has been added to $user->name's project list.");
                                break;
                            } else {
                                return array("Error: $project->name could not be added to $user->name's project list.");
                                break;
                            }
                        } else {
                            return array("Error: No project was found with ID: ".$commandArray['projectID']);
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:projects:add [id] [projectID]");
                    break;
                }
            case "user:projects:remove":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: user:projects:remove [id] [projectID]");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray['id'] = $cutInput[$i];
                    }
                    if ($i == 2) {
                        $commandArray['projectID'] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: user:projects:remove [id] [projectID]");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'id' => 'required|numeric',
                    'projectID' => 'required|numeric',
                ]);

                if (!$validation->fails()) {
                    $user = User::where('id', $commandArray['id'])->first();
                    if ($user != NULL) {
                        $project = Project::where('id', $commandArray['projectID'])->first();
                        if ($project != NULL) {
                            $currentProjects = $user->Projects();
                            if ($currentProjects != NULL) {
                                if ($currentProjects[0] == 'all') {
                                    return array("This user is an admin, therefore they have access to all projects.");
                                    break;
                                } else {
                                    // IF USER HAS CURRENT PROJECTS
                                    if (in_array($commandArray['projectID'], $currentProjects)) {
                                        for ($i = 0; $i < sizeof($currentProjects); $i++) {
                                            if ($currentProjects[$i] == $commandArray['projectID']) {
                                                unset($currentProjects[$i]);
                                            }
                                        }
                                    } else {
                                        return array("Error: $user->name doesn't have $project->name in their project list.");
                                        break;
                                    }
                                }
                            } else {
                                // IF USER HAS NO CURRENT PROJECTS
                                return array("Error: $user->name doesn't have $project->name in their project list.");
                                break;
                            }
                            $user->permitted_projects = json_encode($currentProjects);
                            $result = $user->Save();

                            if ($result) {
                                return array("Success: $project->name has been removed from $user->name's project list.");
                                break;
                            } else {
                                return array("Error: $project->name could not be removed from $user->name's project list.");
                                break;
                            }
                        } else {
                            return array("Error: No project was found with ID: ".$commandArray['projectID']);
                            break;
                        }
                    } else {
                        return array("Error: No user was found with ID: ".$commandArray['id']);
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: user:projects:remove [id] [projectID]");
                    break;
                }
            case "project:get":
                $commandArray = array();
                if (sizeof($cutInput) == 1) {
                    return array("Format: project:get [searchInput/id/'all']");
                    break;
                }
                for ($i = 0; $i < sizeof($cutInput); $i++) {
                    if ($i == 1) {
                        $commandArray["searchInput"] = $cutInput[$i];
                    }
                    if ($cutInput[$i] == "-help" || $cutInput[$i] == "-h") {
                        return array("Format: project:get [searchInput/id/'all']");
                        break;
                    }
                }

                $validation = Validator::make($commandArray, [
                    'searchInput' => 'required',
                ]);

                if (!$validation->fails()) {
                    if ($commandArray['searchInput'] == 'all') {
                        $entries = Project::all();
                    } else {
                        $search = $commandArray['searchInput'];
                        $entries = Project::where('id', $commandArray['searchInput'])->orWhere(function ($query) use ($search) {
                            $query->where('status', 'LIKE', "%$search%")
                                  ->orWhere('name', 'LIKE', "%$search%");
                        })->get();
                    }

                    if (sizeof($entries) > 0) {
                        $returnArray = array();
                        foreach ($entries as $entry) {
                            array_push($returnArray, "$entry->id | $entry->name | $entry->version | $entry->status | $entry->secretKey | $entry->accessUser | $entry->accessPass");
                        }
                        return $returnArray;
                        break;
                    } else {
                        return array("No projects found.");
                        break;
                    }
                } else {
                    return array("Error: Invalid format.", "Format: project:get [searchInput/id/'all']");
                    break;
                }
            default:
                return array("Error: Command not recognised.");
                break;
        }
    }

    // Unpack project files
    public function unpackProject($zipFile, $folderStructure, $projectName, $secretKey, $accessUser, $accessPass) {
        $secret = env('CUSTOM_HOST_USER');
        $zipFile->storeAs("Project_Uploads/$projectName/", $projectName.'.zip');

        $ZIP_ERROR = [
            ZipArchive::ER_EXISTS => 'File already exists.',
            ZipArchive::ER_INCONS => 'Zip archive inconsistent.',
            ZipArchive::ER_INVAL => 'Invalid argument.',
            ZipArchive::ER_MEMORY => 'Malloc failure.',
            ZipArchive::ER_NOENT => 'No such file.',
            ZipArchive::ER_NOZIP => 'Not a zip archive.',
            ZipArchive::ER_OPEN => "Can't open file.",
            ZipArchive::ER_READ => 'Read error.',
            ZipArchive::ER_SEEK => 'Seek error.',
          ];

        $zip = new ZipArchive;
        $res = $zip->open(storage_path("app/Project_Uploads/$projectName/") . $projectName . '.zip');
        if ($res === TRUE) {
            $zip->extractTo(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'));
            $zip->close();
        } else {
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);
            return "Could not unzip uploaded project files. " . $ZIP_ERROR[$res];
        }

        // If folder structure is laravel
        if ($folderStructure == "laravel") {
            $unpackedPath = storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/');

            if (!file_exists($unpackedPath . '.env')) {
                Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);
                return "Wrong directory structure.";
            }

            $addTo_htaccess = "AuthGroupFile /dev/null\nAuthType Basic\nAuthUserFile /home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd\nAuthName ".'"Please Authenticate"'."\nrequire valid-user\nErrorDocument 401 ".'"Unauthorized Access"'."\nOptions -Indexes\n";
            $addTo_htaccess .= file_get_contents($unpackedPath . 'public/.htaccess');
            file_put_contents($unpackedPath . 'public/.htaccess', $addTo_htaccess);

            // CREATE HTPASSWD
            mkdir("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/", 0755, true);
            $htpasswd = fopen("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd", 'w');
            $pass = Self::crypt_apr1_md5($accessPass);
            fwrite($htpasswd, "$accessUser:$pass");

            // Edit Index.php
            $indexToEdit = file($unpackedPath . 'public/index.php');
            $indexToEdit = array_map(function($indexToEdit) use ($projectName) {
                return stristr($indexToEdit,"require __DIR__.'/../vendor/autoload.php';") ? "require __DIR__.'/../../../Projects/$projectName/vendor/autoload.php';\n" : $indexToEdit;
              }, $indexToEdit);
            file_put_contents($unpackedPath . 'public/index.php', implode('', $indexToEdit));

            $indexToEdit = file($unpackedPath . 'public/index.php');
            $indexToEdit = array_map(function($indexToEdit) use ($projectName) {
                return stristr($indexToEdit, '$app = ' . "require_once __DIR__.'/../bootstrap/app.php';") ? '$app = ' . "require_once __DIR__.'/../../../Projects/$projectName/bootstrap/app.php';\n" : $indexToEdit;
              }, $indexToEdit);
            file_put_contents($unpackedPath . 'public/index.php', implode('', $indexToEdit));

            // MOVE FILES TO CORRECT FOLDERS
            Self::copyr($unpackedPath . 'public', "/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/");
            Self::copyr(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'), "/home/$secret/domains/tomvdbroecke.com/Projects/$projectName/");
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);

            return true;
        }

        // If folder structure is standard
        if ($folderStructure == "standard") {
            $unpackedPath = storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/');

            $addTo_htaccess = "AuthGroupFile /dev/null\nAuthType Basic\nAuthUserFile /home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd\nAuthName ".'"Please Authenticate"'."\nrequire valid-user\nErrorDocument 401 ".'"Unauthorized Access"'."\nOptions -Indexes\n";
            $addTo_htaccess .= file_get_contents($unpackedPath . '.htaccess');
            file_put_contents($unpackedPath . '.htaccess', $addTo_htaccess);

            // CREATE HTPASSWD
            mkdir("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/", 0755, true);
            $htpasswd = fopen("/home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd", 'w');
            $pass = Self::crypt_apr1_md5($accessPass);
            fwrite($htpasswd, "$accessUser:$pass");

            Self::copyr(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'), "/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/");
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);

            return true;
        }
    }

    // Overwrite project files <--- WRITE THIS
    public function overwriteProject($zipFile, $folderStructure, $projectName, $secretKey, $accessUser, $accessPass) {
        $secret = env('CUSTOM_HOST_USER');
        $zipFile->storeAs("Project_Uploads/$projectName/", $projectName.'.zip');

        $ZIP_ERROR = [
            ZipArchive::ER_EXISTS => 'File already exists.',
            ZipArchive::ER_INCONS => 'Zip archive inconsistent.',
            ZipArchive::ER_INVAL => 'Invalid argument.',
            ZipArchive::ER_MEMORY => 'Malloc failure.',
            ZipArchive::ER_NOENT => 'No such file.',
            ZipArchive::ER_NOZIP => 'Not a zip archive.',
            ZipArchive::ER_OPEN => "Can't open file.",
            ZipArchive::ER_READ => 'Read error.',
            ZipArchive::ER_SEEK => 'Seek error.',
          ];

        $zip = new ZipArchive;
        $res = $zip->open(storage_path("app/Project_Uploads/$projectName/") . $projectName . '.zip');
        if ($res === TRUE) {
            $zip->extractTo(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'));
            $zip->close();
        } else {
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);
            return "Could not unzip uploaded project files. " . $ZIP_ERROR[$res];
        }

        // If folder structure is laravel
        if ($folderStructure == "laravel") {
            $unpackedPath = storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/');

            if (!file_exists($unpackedPath . '.env')) {
                Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);
                return "Wrong directory structure.";
            }

            // Edit .htaccess
            $addTo_htaccess = "AuthGroupFile /dev/null\nAuthType Basic\nAuthUserFile /home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd\nAuthName ".'"Please Authenticate"'."\nrequire valid-user\nErrorDocument 401 ".'"Unauthorized Access"'."\nOptions -Indexes\n";
            $addTo_htaccess .= file_get_contents($unpackedPath . 'public/.htaccess');
            file_put_contents($unpackedPath . 'public/.htaccess', $addTo_htaccess);

            // Edit Index.php
            $indexToEdit = file($unpackedPath . 'public/index.php');
            $indexToEdit = array_map(function($indexToEdit) use ($projectName) {
                return stristr($indexToEdit,"require __DIR__.'/../vendor/autoload.php';") ? "require __DIR__.'/../../../Projects/$projectName/vendor/autoload.php';\n" : $indexToEdit;
              }, $indexToEdit);
            file_put_contents($unpackedPath . 'public/index.php', implode('', $indexToEdit));

            $indexToEdit = file($unpackedPath . 'public/index.php');
            $indexToEdit = array_map(function($indexToEdit) use ($projectName) {
                return stristr($indexToEdit, '$app = ' . "require_once __DIR__.'/../bootstrap/app.php';") ? '$app = ' . "require_once __DIR__.'/../../../Projects/$projectName/bootstrap/app.php';\n" : $indexToEdit;
              }, $indexToEdit);
            file_put_contents($unpackedPath . 'public/index.php', implode('', $indexToEdit));

            // MOVE FILES TO CORRECT FOLDERS
            Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/Projects/$projectName", true);
            Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/", true);
            Self::copyr($unpackedPath . 'public', "/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/");
            Self::copyr(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'), "/home/$secret/domains/tomvdbroecke.com/Projects/$projectName/");
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);

            return true;
        }

        // If folder structure is standard
        if ($folderStructure == "standard") {
            $unpackedPath = storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/');

            $addTo_htaccess = "AuthGroupFile /dev/null\nAuthType Basic\nAuthUserFile /home/$secret/domains/tomvdbroecke.com/.htpasswd/public_html/Projects/".$projectName."_public_$secretKey/.htpasswd\nAuthName ".'"Please Authenticate"'."\nrequire valid-user\nErrorDocument 401 ".'"Unauthorized Access"'."\nOptions -Indexes\n";
            $addTo_htaccess .= file_get_contents($unpackedPath . '.htaccess');
            file_put_contents($unpackedPath . '.htaccess', $addTo_htaccess);

            Self::rrmdir("/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/", true);
            Self::copyr(storage_path("app/Project_Uploads/$projectName/" . $projectName . '_unzipped/'), "/home/$secret/domains/tomvdbroecke.com/public_html/Projects/$projectName" .'_public_' ."$secretKey/");
            Self::rrmdir(storage_path("app/Project_Uploads/$projectName"), true);

            return true;
        }
    }

    // Remove dir
    function rrmdir($dir, $remSelf) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($dir."/".$object))
                    Self::rrmdir($dir."/".$object, true);
                    else
                    unlink($dir."/".$object); 
                } 
            }
            if ($remSelf) {
                rmdir($dir); 
            }
        } 
    }

    // APR1-MD5 encryption method (windows compatible)
    function crypt_apr1_md5($plainpasswd)
    {
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $len = strlen($plainpasswd);
        $text = $plainpasswd.'$apr1$'.$salt;
        $bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
        for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
        for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
        $bin = pack("H32", md5($text));
        for($i = 0; $i < 1000; $i++)
        {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) $new .= $salt;
            if ($i % 7) $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        $tmp = '';
        for ($i = 0; $i < 5; $i++)
        {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) $j = 5;
            $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
        }
        $tmp = chr(0).chr(0).$bin[11].$tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
        "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
    
        return "$"."apr1"."$".$salt."$".$tmp;
    }

    public function copyr($source, $dest)
    {
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }
        if (is_file($source)) {
            return copy($source, $dest);
        }
        if (!is_dir($dest)) {
            mkdir($dest);
        }
        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            Self::copyr("$source/$entry", "$dest/$entry");
        }
        $dir->close();
        return true;
    }
}
