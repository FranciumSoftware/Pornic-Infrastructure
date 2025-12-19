articleName=[];
articlePrice=[];
articleImage=[];
articleDescription=[];
arcticleID=[];
function addToList(ID, Nom, price, Image, Description){
    arcticleID.push(ID);
    articleName.push(Nom);
    articlePrice.push(price);
    articleImage.push(Image);
    articleDescription.push(Description);
    
    callArrays();
}

function callArrays(){
    console.log(articleName);
}