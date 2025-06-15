import { api } from '../../util/axios.js';

export default function () {
    return {
        baseOption: '내용이 없습니다.',
        addOption: '내용이 없습니다.',
        isHistory: false,
        init() {
            this.waitForDiag();
        },
        waitForDiag() {
            const check = setInterval(() => {
              const diag = Alpine.store('shared')?.diag;
              if (diag) {
                clearInterval(check);
                this.baseOption = diag.data?.diag_base_option?.trim() || '내용이 없습니다.';
                this.addOption = diag.data?.diag_add_option?.trim() || '내용이 없습니다.';
              }
            }, 100);
        }
    }
}