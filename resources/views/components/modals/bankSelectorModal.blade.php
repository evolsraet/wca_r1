<div class="contentWrapper" x-data="bankSelectorModal()">
    <div class="tabs">
      <div class="tab" :class="{ 'active': selectedTab === 'bank' }" @click="showTab('bank')">은행</div>
      <div class="tab" :class="{ 'active': selectedTab === 'stock' }" @click="showTab('stock')">증권</div>
    </div>
  
    <div id="bank" class="tab-content" :class="{ 'active': selectedTab === 'bank' }" x-show="selectedTab === 'bank'" x-transition>
      <div class="grid">
        <template x-for="bank in banks" :key="bank.name">
            <div class="bank-item" @click="selectBank(bank.name)">
                <img :src="bank.image" class="bank-icon" :alt="bank.name">
                <div class="bank-label" x-text="bank.name"></div>
            </div>
        </template>
      </div>
    </div>
  
    <div id="stock" class="tab-content" :class="{ 'active': selectedTab === 'stock' }" x-show="selectedTab === 'stock'" x-transition>
      <div class="grid">
        <template x-for="stock in stocks" :key="stock.name">
            <div class="bank-item" @click="selectBank(stock.name)">
                <img :src="stock.image" class="bank-icon" :alt="stock.name">
                <div class="bank-label" x-text="stock.name"></div>
            </div>
        </template>
      </div>
    </div>
  </div>

  <style>
    .contentWrapper {
      max-width: 600px;
      margin: auto;
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .header {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      text-align: center;
    }

    .tabs {
      display: flex;
      border-bottom: 1px solid #e0e0e0;
      margin-bottom: 20px;
    }

    .tab {
      flex: 1;
      padding: 12px 0;
      text-align: center;
      cursor: pointer;
      font-weight: 500;
      color: #999;
      border-bottom: 2px solid transparent;
      transition: all 0.2s ease;
    }

    .tab.active {
      color: black;
      border-bottom: 2px solid black;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }

    .bank-item {
      background: #f8f8f8;
      border-radius: 8px;
      padding: 16px 0;
      text-align: center;
      cursor: pointer;
      transition: 0.2s;
    }

    .bank-item:hover {
      background: #ececec;
    }

    .bank-icon {
      height: 32px;
      margin-bottom: 8px;
    }

    .bank-label {
      font-size: 14px;
      font-weight: 500;
    }
  </style>