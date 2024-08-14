import axios from 'axios';

export const downloadExcel = async (endpoint, orderColumn = 'created_at', orderDirection = 'asc', fileName = 'data.xlsx') => {
    try {
        let url = `${endpoint}?order_by=${orderColumn}&order_dir=${orderDirection}`;

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
