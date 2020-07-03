def git_url = 'https://github.com/initedit/simple-storage-solution.git'
def git_branch = 'master'

pipeline
{
    agent {
        label 'web2'
    }
    stages
    {
        /*stage('Git-checkout')
        {
            steps
            {
                git credentialsId: 'github', url: git_url , branch: git_branch
            }
        }*/
        
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
                tar -cpf uploads.tar uploads
                mv uploads.tar ..
                rm -rf /home/initedit2/web/ssd.initedit.com/public_html/*
                cp -a $WORKSPACE/* /home/initedit2/web/ssd.initedit.com/public_html/
                /usr/bin/cp $WORKSPACE/.htaccess /home/initedit2/web/ssd.initedit.com/public_html/


                mv  ../uploads.tar .
                tar -xpf uploads.tar
                rm -rf uploads.tar
                chown -R initedit2:initedit2 *
                '''
            }
        }
        
        stage('Smoke-test')
        {
            steps
            {
                sh '''
                ab -n 4 -c 2 https://ssd.initedit.com/
                echo "somke-test"
                '''
            }
        }
    }
}
