let baseUrl = 'api/v1/';
let method = 'items/list';

let url = baseUrl + method;
fetch(url).then((res)=>res.json()).then((res)=>console.log(res[150].name)).catch((e)=>console.log(e));
