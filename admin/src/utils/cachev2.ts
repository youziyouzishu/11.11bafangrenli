// utils/cache.js
const cachev2 = {
  set(key, value, expire = null) {
    const data = { value, expire: expire ? Date.now() + expire : null };
    localStorage.setItem(key, JSON.stringify(data));
  },
  get(key) {
    const data = JSON.parse(localStorage.getItem(key));
    if (data) {
      if (data.expire === null || data.expire >= Date.now()) {
        return data.value;
      }
      localStorage.removeItem(key); // 移除过期数据
    }
    return null;
  },
  remove(key) {
    localStorage.removeItem(key);
  },
  clear() {
    localStorage.clear();
  },
};

export default cachev2;
