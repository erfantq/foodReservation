let startWeekDate;
let endWeekDate;
let date = new Date();
let selectedDayDate = date;
let dayGlob;
let lastType;

const urlParams = new URLSearchParams(window.location.search);  
const param1 = urlParams.get('day');
if(param1) {
    dayGlob = param1;
} else {
    dayGlob = date.getDay();
    if (dayGlob == 4 || dayGlob == 5) {
        dayGlob = 6;
    }
}
const param2 = urlParams.get('type');
if(param2) {
    lastType = param2;
    if(param2 == "food")
        document.getElementById("foodLink").style.borderColor = 'white';
    else if(param2 == "drink")
        document.getElementById("drinkLink").style.borderColor = 'white';
} else {
    lastType = 'food';
    document.getElementById("foodLink").style.borderColor = 'white';
}

let lastSelectedDay = document.getElementById("day" + dayGlob);
lastSelectedDay.style.color = "#dc3545";


async function getType(event, type) {
    event.preventDefault();


    if(type == 'food') {
        lastType = 'food';
        document.getElementById("drinkLink").style.borderColor = 'rgb(33, 37, 41)';
        document.getElementById("foodLink").style.borderColor = 'white';  
    } else {
        lastType = 'drink';
        document.getElementById("drinkLink").style.borderColor = 'white';
        document.getElementById("foodLink").style.borderColor = 'rgb(33, 37, 41)';
    }
    
    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("editFoods.php?day=" + dayGlob + "&type=" + lastType);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';
    
    mainElem.innerHTML = textedData;

}

async function getFoods(event, day = -1) {
    event.preventDefault();

    if (day != -1 && day != dayGlob) {
        
        dayGlob = day;
        lastSelectedDay.style.color = "rgba(255, 255, 255, 0.55)";
        event.target.style.color = "#dc3545";
        lastSelectedDay = event.target;
    }
    

    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("editFoods.php?day=" + dayGlob + "&type=" + lastType);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;

}

async function addFood(event) {
    event.preventDefault();

    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("editFoods.php?day=" + dayGlob + "&type=" + lastType + "&add=true");

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;
}

async function getChoosedFood(id) {

    let btnId = id;
    let numberOfBtn = btnId.slice(-1);
    
    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";
    
    let fetchedData = await fetch("editFoods.php?day=" + dayGlob + "&type=" + lastType + "&selected=" + numberOfBtn);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;

}

async function getDeletedFood(event, id) {

    event.preventDefault();

    let btnId = id;
    let numberOfBtn = btnId.slice(-1);
    
    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";


    let fetchedData = await fetch("editFoods.php?deleted=" + numberOfBtn);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;
}

function addDays(startDate, d) {
    startDate.setTime(startDate.getTime() 
    + (d * 24 * 60 * 60 * 1000));
    return startDate;
}

function subtractDays(startDate, d) {
    startDate.setTime(startDate.getTime() 
    - (d * 24 * 60 * 60 * 1000));
    return startDate;
}