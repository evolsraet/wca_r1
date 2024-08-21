export const downloadExcel = (endpoint, orderColumn = 'created_at', orderDirection = 'asc', stat = '', role = '', search_text) => {
    let url = `/excelDown/${endpoint}`;

    if (stat == 'all') {
        stat = '';
    }
    if (role == 'all') {
        role = '';
    }

    if (endpoint === 'users') {
        let whenLabel = '';

        if (stat !== '' && role !== '') {
            whenLabel = `where=users.status:${stat}|users.roles:${role}&`;
        } else if (stat) {
            whenLabel = `where=users.status:${stat}&`;
        } else if (role) {
            whenLabel = `where=users.roles:${role}&`;
        }

        url += `?${whenLabel}search_text=${search_text}&order_column=${orderColumn}&order_direction=${orderDirection}`;
    } else if (endpoint === 'auctions') {
        let whenLabel = '';
        if (stat !== '') {
            whenLabel = `where=auctions.status:${stat}&`;
        }
        url += `?${whenLabel}search_text=${search_text}&order_column=${orderColumn}&order_direction=${orderDirection}`;
    } else if (endpoint === 'reviews') {
        let whenLabel = '';
        if (stat !== '') {
            whenLabel = `where=reviews.star:${stat}&`;
        }
        url += `?${whenLabel}search_text=${search_text}&with=dealer,auction,user&order_column=${orderColumn}&order_direction=${orderDirection}`;
    }

    window.open(url, '_blank');
};
