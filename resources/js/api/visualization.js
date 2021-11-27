import request from '@/utils/request';

export function fetchList(query) {
  return request({
    url: '/visualizations',
    method: 'get',
    params: query,
  });
}
