function togglebutton(button){
    let allRoom=document.querySelectorAll('.book_room');
    allRoom.forEach(function(btn){
        if(btn!==button){
            btn.innerText="Click to Book";
            btn.style.backgroundColor="";
            btn.style.color="";

        }
    });
    if(button.innerText==='Click to Book'){
        button.innerText="Booked";
        button.style.backgroundColor="green";
        button.style.color="white";
         let hotel=document.getElementById("hotel-name").value;
         let room=document.getElementById("hotel-room").value;
        let conf=confirm(`In Hotel:${hotel} \nRoom Type:${room} is Booked`);
        if(conf){
            alert("Room is Booked");
            setTimeout(()=>{
                window.location.href="Food.html";
            },2000);
        }else{
            alert("Booking is Cancelled");
        }

    }else{
            button.innerText="Click to Book";
            button.style.backgroundColor="";
            button.style.color="";

    }

}