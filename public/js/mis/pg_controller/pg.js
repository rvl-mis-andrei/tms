export function pg_array(vs){
    let str = {
           'dashboard':'0001',
           'training_videos':'0002',
           'new_training_videos':'0021',
           'videos_categories':'0003',
           'activity_logs':'0004',
   };
   return str[vs];
}
