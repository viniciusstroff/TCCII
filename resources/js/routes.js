import ReportList from './Reports/ReportList';
import ReportAdd from './Reports/ReportAdd';
import ReportScore from './Reports/ReportScore';
import Example from './components/ExampleComponent';
import ReportPendingList from './ReportsPending/ReportPendingList';
import Dashboard from './Dashboard/Dashboard';


//https://router.vuejs.org/guide/essentials/nested-routes.html agrupamento de rotas
const routes = [
    {
        path: '/audits',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/reports',
        name: 'reports-list',
        component: ReportList
    },
    {
        path: '/report',
        name: 'report-add',
        component: ReportAdd
    },
    {
        path: '/reports/report/:id',
        name: 'report-edit',
        component: ReportAdd
    },
    {
        path: '/reports/report/:id/scores',
        name: 'report-scores-show',
        component: ReportScore
    },
    {
        path: '/report-pending',
        name: 'report pending list',
        component: ReportPendingList
    }
];

export default routes