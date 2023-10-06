export default class SearchUtils{

    static advancedSearch = (array,searchString) => {
        let results = [];
        let searchArray = searchString.split(" ");
        array.forEach((item) => {
            let match = true;
            searchArray.forEach((searchItem) => {
                if(!item.name.toLowerCase().includes(searchItem.toLowerCase())){
                    match = false;
                }
            })
            if(match){
                results.push(item);
            }
        })
        return results;
    }

}
