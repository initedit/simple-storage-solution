def git_url = 'https://github.com/initedit/simple-storage-solution.git'
def git_branch = 'master'

pipeline
{
    agent {
        label 'web2'
    }
    stages
    {
        stage('Git-checkout')
        {
            steps
            {
                git credentialsId: 'github', url: git_url , branch: git_branch
            }
        }
        
        stage('Sonarqube-anaylysis')
        {
            steps
            {
                sh '''
                echo "sonarqube analysis"
                '''
            }
        }
        
        stage('Build')
        {
            steps
            {
                sh '''
                echo "build"
                '''
            }
        }
        
        stage('Deploy')
        {
            steps
            {
                sh '''
                cd /home/initedit2/web/ssd.initedit.com/public_html/
                tar -czf uploads.tar.gz uploads
                mv uploads.tar.gz ..
                rm -rf /home/initedit2/web/ssd.initedit.com/public_html/*
                cp -a $WORKSPACE/* /home/initedit2/web/ssd.initedit.com/public_html/

                mv  ../uploads.tar.gz .
                tar -xzf uploads.tar.gz
                chown -R initedit2:initedit2 *
                '''
            }
        }
        
        stage('Smoke-test')
        {
            steps
            {
                sh '''
                ab -n 10 -c 2 https://ssd.initedit.com
                echo "somke-test"
                '''
            }
        }
    }
}
