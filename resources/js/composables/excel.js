import axios from 'axios';

export const downloadExcel = async (endpoint, orderColumn = 'created_at', orderDirection = 'asc', fileName = 'data.xlsx',stat='',role='',search_text) => {
    try {
        //let url = `/excelDown/${endpoint}?order_by=${orderColumn}&order_dir=${orderDirection}`;
        let url = `/excelDown/${endpoint}`;

        if(stat == 'all'){
            stat='';
        }
        if(role == 'all'){
            role='';
        }

        if(endpoint == 'users'){
            let whenLabel = '';

            if(stat != '' && role != ''){
                whenLabel = `where=users.status:${stat}|users.roles:${role}&`;
            } else if(stat){
                whenLabel = `where=users.status:${stat}&`;
            } else if(role){
                whenLabel = `where=users.roles:${role}&`;
            }

            url += `?${whenLabel}search_text=${search_text}&order_column=${orderColumn}&order_direction=${orderDirection}`;
        }else if(endpoint == 'auctions'){
            let whenLabel = '';
            if(stat != ''){
                whenLabel = `where=auctions.status:${stat}&`;
            }
            url += `?${whenLabel}search_text=${search_text}&order_column=${orderColumn}&order_direction=${orderDirection}`;
        }else if(endpoint == 'reviews'){
            let whenLabel = '';
            if(stat != ''){
                whenLabel = `where=reviews.star:${stat}&`;
            }
            url += `?${whenLabel}search_text=${search_text}&with=dealer,auction,user&order_column=${orderColumn}&order_direction=${orderDirection}`;
        }

        const response = await axios.get(url, {
            responseType: 'blob' 
        });

        const urlBlob = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = urlBlob;
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        console.error("Failed to download Excel file:", error);
    }
};
