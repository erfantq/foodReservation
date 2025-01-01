// const date = new Date();
// const day = date.getDay();
// window.location.assign("reserve.php?" + day);
let startWeekDate;
let endWeekDate;
let date = new Date();
let selectedDayDate = date;

let dayGlob = date.getDay();
if (dayGlob == 4 || dayGlob == 5) {
    selectedDayDate =  addDays(selectedDayDate, 6-dayGlob);
    dayGlob = 6;
}
let lastSelectedDay = document.getElementById("day" + dayGlob);
let lastType = 'food';
document.getElementById("foodLink").style.borderColor = 'white';
lastSelectedDay.style.color = "#dc3545";

function getWeek(startWeekDate, endWeekDate) {
    console.log(startWeekDate);
    console.log(endWeekDate);
    this.startWeekDate = new Date(startWeekDate);
    this.endWeekDate = new Date(endWeekDate);
    
    jalaaliStart = this.startWeekDate.toLocaleDateString('fa-IR');
    jalaaliEnd = this.endWeekDate.toLocaleDateString('fa-IR');

    const dateDisplay = document.getElementById("dateDisplay");
    dateDisplay.innerText = jalaaliStart + ' -- ' + jalaaliEnd;
}

function getDayOfWeek(day) {
    window.location.assign("reserve.php?day=" + day);
}

async function getFoods(event, day) {
    event.preventDefault();

    if (day != dayGlob) {
        if(day == 6) {
            selectedDayDate = this.startWeekDate;

        } else {
            if(day < dayGlob) {
                selectedDayDate =  subtractDays(selectedDayDate, dayGlob-day);
            } else {
                selectedDayDate =  addDays(selectedDayDate, day-dayGlob);
            }
        }
        dayGlob = day;
        console.log(dayGlob);
    }
    
    lastSelectedDay.style.color = "rgba(255, 255, 255, 0.55)";
    event.target.style.color = "#dc3545";
    lastSelectedDay = event.target;

    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";


    let fetchedData = await fetch("foods.php?day=" + dayGlob + "&type=" + lastType);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;

}
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

    let fetchedData = await fetch("foods.php?day=" + dayGlob + "&type=" + lastType);

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


    
    let fetchedData = await fetch("foods.php?day=" + dayGlob + "&type=" + lastType + "&selected=" + numberOfBtn);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';

    mainElem.innerHTML = textedData;

}

async function weekChanger(event, value) {
    event.preventDefault();
    
    document.getElementById("mySpinner").style.display = 'flex';


    if(value == 1) {
        startWeekDate = addDays(this.startWeekDate, 7);
        endWeekDate = addDays(this.endWeekDate, 7);
        selectedDayDate =  addDays(selectedDayDate, 7);
    } else {
        startWeekDate = subtractDays(this.startWeekDate, 7);
        endWeekDate = subtractDays(this.endWeekDate, 7);
        selectedDayDate =  subtractDays(selectedDayDate, 7);
    }

    jalaaliStart = startWeekDate.toLocaleDateString('fa-IR');
    jalaaliEnd = endWeekDate.toLocaleDateString('fa-IR');

    const dateDisplay = document.getElementById("dateDisplay");
    dateDisplay.innerText = jalaaliStart + ' -- ' + jalaaliEnd;

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    let fetchedData = await fetch("foods.php?day=" + dayGlob + "&type=" + lastType + "&week=" + value);

    let textedData = await fetchedData.text();

    document.getElementById("mySpinner").style.display = 'none';
    
    mainElem.innerHTML = textedData;
}

async function cancelFood(event) {

    event.preventDefault();

    document.getElementById("mySpinner").style.display = 'flex';

    const mainElem = document.querySelector("main");
    mainElem.innerHTML = "";

    console.log("selectedDayDate: " + selectedDayDate);

    const year = selectedDayDate.getFullYear();
    const month = String(selectedDayDate.getMonth() + 1).padStart(2, '0'); // Months are zero-based, so add 1
    const day = String(selectedDayDate.getDate()).padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;
    console.log(formattedDate);

    let fetchedData = await fetch("foods.php?day=" + dayGlob + "&type=" + lastType + "&cancel=" + formattedDate);

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

